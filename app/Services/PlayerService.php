<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Support\Facades\DB;

class PlayerService
{
    public static function copyGoR(string $from, string $to)
    {
        Player::query()->update([
            $to => DB::raw($from),
        ]);
    }

    public static function resetSlots()
    {
        Player::query()->update([
            's0' => 0.0,
            's1' => 0.0,
            's2' => 0.0,
            's3' => 0.0,
            's4' => 0.0,
            's5' => 0.0,
            's6' => 0.0,
            's7' => 0.0,
            's8' => 0.0,
            's9' => 0.0,
        ]);
    }
}
