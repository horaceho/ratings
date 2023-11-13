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

        $query->whereDate('records.date', '>=', $trial->from);
        $query->whereDate('records.date', '<=', $trial->till);

        $query->where('handicap', '<=', $trial->handicap);

        $query->orderBy('date');
        $query->with('blackPlayer');
        $query->with('whitePlayer');

        $query->chunk(100, function (Collection $records) use($trial) {
            foreach ($records as $record) {
                $black = $trial->results()->create([
                    'record_id' => $record->id,
                    'date' => $record->date,
                    'player' => $record->black,
                    'opponent' => $record->white,
                    'winner' => $record->winner,
                    'entrant_id' => $record->blackPlayer->id,
                    'opposer_id' => $record->whitePlayer->id,
                    'slot' => $trial->slot,
                    'pl_result' => $record->black === $record->winner ? 1.0 : 0.0,
                    'op_result' => $record->black === $record->winner ? 0.0 : 1.0,
                ]);

                $white = $trial->results()->create([
                    'record_id' => $record->id,
                    'date' => $record->date,
                    'player' => $record->white,
                    'opponent' => $record->black,
                    'winner' => $record->winner,
                    'entrant_id' => $record->whitePlayer->id,
                    'opposer_id' => $record->blackPlayer->id,
                    'slot' => $trial->slot,
                    'pl_result' => $record->white === $record->winner ? 1.0 : 0.0,
                    'op_result' => $record->white === $record->winner ? 0.0 : 1.0,
                ]);

                $pl_rating = $black->entrant->rating($trial->slot);
                $op_rating = $white->entrant->rating($trial->slot);

                $pl_update = $pl_rating;
                $op_update = $op_rating;

                if ($trial->algorithm === 'egf') {
                    $pl_update = self::$ers->update(
                        $pl_rating,
                        $op_rating,
                        $black->pl_result,
                        $trial->meta['con_div'] ?? config('ratings.algorithms.egf.defaults.con_div'),
                        $trial->meta['con_pow'] ?? config('ratings.algorithms.egf.defaults.con_pow'),
                    );
                    $op_update = self::$ers->update(
                        $op_rating,
                        $pl_rating,
                        $white->pl_result,
                        $trial->meta['con_div'] ?? config('ratings.algorithms.egf.defaults.con_div'),
                        $trial->meta['con_pow'] ?? config('ratings.algorithms.egf.defaults.con_pow'),
                    );
                } else {
                    ;
                }

                $black->update([
                    'pl_rating' => $pl_rating,
                    'pl_update' => $pl_update,
                    'pl_change' => round($pl_update - $pl_rating, 3),
                    'op_rating' => $op_rating,
                    'op_update' => $op_update,
                    'op_change' => round($op_update - $op_rating, 3),
                ]);

                $white->update([
                    'pl_rating' => $op_rating,
                    'pl_update' => $op_update,
                    'pl_change' => round($op_update - $op_rating, 3),
                    'op_rating' => $pl_rating,
                    'op_update' => $pl_update,
                    'op_change' => round($pl_update - $pl_rating, 3),
                ]);

                $black->entrant->update([
                    $trial->slot => $pl_update,
                ]);

                $white->entrant->update([
                    $trial->slot => $op_update,
                ]);
            }
        });

        return $trial->results()->count();
    }
}

ResultService::$ers = new Ers\Ers();
