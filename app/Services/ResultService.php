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
        Player::query()->update([
            'gor' => 0.0
        ]);
        $trial->results()->delete();
        self::generate($trial);
        self::calculate($trial);
    }

    public static function generate(Trial $trial)
    {
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
            $entrant = $result->entrant->rating;
            $opposer = $result->opposer->rating;

            $updated = $ers->update(
                $entrant,
                $opposer,
                $result->win,
            );

            $result->entrant->update([
                'gor' => $updated,
            ]);

            $result->update([
                'rating' => $entrant,
                'update' => $updated,
            ]);
        }
    }
}
