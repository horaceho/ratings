<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Record;
use App\Models\Result;
use App\Models\Trial;
use HoraceHo\Ers;
use Illuminate\Support\Collection;

class ResultService
{
    public static function refresh(Trial $trial)
    {
        self::resetPlayersGoR($trial);
        self::generate($trial);
        self::calculate($trial);
    }

    public static function resetPlayersGoR(Trial $trial)
    {
        Player::query()->update([
            $trial->slot => 0.0,
        ]);
    }

    public static function generate(Trial $trial)
    {
        $trial->results()->delete();

        $query = Record::query();

        if (! empty($trial->organization)) {
            $query->where('organization', $trial->organization);
        }
        if (! empty($trial->match)) {
            $query->where('match', $trial->match);
        }
        if (! empty($trial->group)) {
            $query->where('group', $trial->group);
        }

        $query->orderBy('date');
        $query->with('blackPlayer');
        $query->with('whitePlayer');

        $query->chunk(100, function (Collection $records) use($trial) {
            foreach ($records as $record) {
                Result::updateOrCreate([
                    'date' => $record->date,
                    'player' => $record->black,
                    'opponent' => $record->white,
                    'winner' => $record->winner,
                ], [
                    'entrant_id' => $record->blackPlayer->id,
                    'opposer_id' => $record->whitePlayer->id,
                    'record_id' => $record->id,
                    'trial_id' => $trial->id,
                    'slot' => $trial->slot,
                ]);
                Result::updateOrCreate([
                    'date' => $record->date,
                    'player' => $record->white,
                    'opponent' => $record->black,
                    'winner' => $record->winner,
                ], [
                    'entrant_id' => $record->whitePlayer->id,
                    'opposer_id' => $record->blackPlayer->id,
                    'record_id' => $record->id,
                    'trial_id' => $trial->id,
                    'slot' => $trial->slot,
                ]);
            }
        });

        return $trial->results()->count();
    }

    public static function calculate(Trial $trial)
    {
        $ers = new Ers\Ers();

        $results = $trial->results()->with('entrant')->with('opposer');
        foreach ($results->lazy() as $result) {
            $entrant = $result->entrant->rating($trial->slot);
            $opposer = $result->opposer->rating($trial->slot);

            $updated = $ers->update(
                $entrant,
                $opposer,
                $result->win,
            );

            $result->entrant->update([
                $trial->slot => $updated,
            ]);

            $result->update([
                'rating' => $entrant,
                'update' => $updated,
            ]);
        }
    }
}
