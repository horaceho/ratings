<?php

namespace App\Services;

use App\Models\Record;
use App\Models\Result;
use App\Models\Trial;
use Illuminate\Support\Collection;

class ResultService
{
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
                    'player_id' => $record->blackPlayer->id,
                    'record_id' => $record->id,
                    'trial_id' => $trial->id,
                ]);
                Result::updateOrCreate([
                    'date' => $record->date,
                    'player' => $record->white,
                    'opponent' => $record->black,
                    'winner' => $record->winner,
                ], [
                    'player_id' => $record->whitePlayer->id,
                    'record_id' => $record->id,
                    'trial_id' => $trial->id,
                ]);
            }
        });

        return $trial->results()->count();
    }
}
