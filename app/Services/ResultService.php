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

        $query->whereDate('records.date', '>=', $trial->from);
        $query->whereDate('records.date', '<=', $trial->till);

        $query->where('handicap', '<=', $trial->handicap);

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
                    'pl_result' => $record->black === $record->winner ? 1.0 : 0.0,
                    'op_result' => $record->black === $record->winner ? 0.0 : 1.0,
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
                    'pl_result' => $record->white === $record->winner ? 1.0 : 0.0,
                    'op_result' => $record->white === $record->winner ? 0.0 : 1.0,
                ]);
            }
        });

        return $trial->results()->count();
    }

    public static function calculate(Trial $trial)
    {
        $results = $trial->results();

        foreach ($results->lazy() as $result) {
            $pl_rating = $result->entrant->rating($trial->slot);
            $op_rating = $result->opposer->rating($trial->slot);

            $pl_update = $pl_rating;
            $op_update = $op_rating;

            if ($trial->algorithm === 'egf') {
                $pl_update = self::$ers->update(
                    $pl_rating,
                    $op_rating,
                    $result->pl_result,
                    $trial->meta['con_div'] ?? config('ratings.algorithms.egf.defaults.con_div'),
                    $trial->meta['con_pow'] ?? config('ratings.algorithms.egf.defaults.con_pow'),
                );
                $op_update = self::$ers->update(
                    $op_rating,
                    $pl_rating,
                    $result->op_result,
                    $trial->meta['con_div'] ?? config('ratings.algorithms.egf.defaults.con_div'),
                    $trial->meta['con_pow'] ?? config('ratings.algorithms.egf.defaults.con_pow'),
                );
            } else {
                ;
            }

            $result->entrant->update([
                $trial->slot => $pl_update,
            ]);

            $result->update([
                'pl_rating' => $pl_rating,
                'pl_update' => $pl_update,
                'pl_change' => round($pl_update - $pl_rating, 3),
                'op_rating' => $op_rating,
                'op_update' => $op_update,
                'op_change' => round($op_update - $op_rating, 3),
            ]);
        }
    }
}

ResultService::$ers = new Ers\Ers();
