<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Record;
use App\Models\Trial;
use Horaceho\Ers;
use Illuminate\Support\Collection;

class ResultService
{
    public static $ers;

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

        $rating_hi = $trial->rating_hi;
        $rating_lo = $trial->rating_lo;
        $query->whereHas('blackPlayer', function($player) use ($rating_hi, $rating_lo) {
            $player->where('rating_init', '>=', $rating_lo)->where('rating_init', '<=', $rating_hi);
        });
        $query->whereHas('whitePlayer', function($player) use ($rating_hi, $rating_lo) {
            $player->where('rating_init', '>=', $rating_lo)->where('rating_init', '<=', $rating_hi);
        });

        $query->orderBy('date');
        $query->with('blackPlayer');
        $query->with('whitePlayer');

        $query->chunk(100, function (Collection $records) use($trial) {
            foreach ($records as $record) {
                $trial->results()->updateOrCreate([
                    'date' => $record->date,
                    'player' => $record->black,
                    'opponent' => $record->white,
                    'winner' => $record->winner,
                ], [
                    'entrant_id' => $record->blackPlayer->id,
                    'opposer_id' => $record->whitePlayer->id,
                    'record_id' => $record->id,
                    'slot' => $trial->slot,
                ]);
                $trial->results()->updateOrCreate([
                    'date' => $record->date,
                    'player' => $record->white,
                    'opponent' => $record->black,
                    'winner' => $record->winner,
                ], [
                    'entrant_id' => $record->whitePlayer->id,
                    'opposer_id' => $record->blackPlayer->id,
                    'record_id' => $record->id,
                    'slot' => $trial->slot,
                ]);
            }
        });

        return $trial->results()->count();
    }

    public static function calculate(Trial $trial)
    {
        $results = $trial->results()
            ->with('entrant')
            ->with('opposer');

        foreach ($results->lazy() as $result) {
            $entrant = $result->entrant->rating($trial->slot);
            $opposer = $result->opposer->rating($trial->slot);

            $updated = $entrant;

            if ($trial->algorithm === 'egf') {
                $updated = self::$ers->update(
                    $entrant,
                    $opposer,
                    $result->win,
                    $trial->meta['con_div'] ?? config('ratings.algorithms.egf.defaults.con_div'),
                    $trial->meta['con_pow'] ?? config('ratings.algorithms.egf.defaults.con_pow'),
                );
            } else {
                ;
            }

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

ResultService::$ers = new Ers\Ers();
