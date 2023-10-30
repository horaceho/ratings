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

    public static function rankings(string $slot)
    {
        return Player::orderBy($slot, 'desc')
            ->withCount([
                'results as win' => function($result) use ($slot) {
                    $result->where('slot', $slot)->where('pl_result', '>', 0.5);
                },
                'results as loss' => function($result) use ($slot) {
                    $result->where('slot', $slot)->where('pl_result', '<', 0.5);
                },
            ])
            ->get()
            ->map(function ($result) use ($slot) {
                return $result->only([
                    'rank',
                    'name',
                    $slot,
                    'total',
                    'win',
                    'loss',
                    'rate',
                    'status',
                ]);
            })
            ->map(function($result) {
                $total = $result['win'] + $result['loss'];
                $rate = $total > 0 ? $result['win'] / $total : 0.0;
                $result['total'] = $total;
                $result['rate'] = round($rate, 3);
                return $result;
            })
            ->map(function($result, $key) {
                $result['rank'] = $key + 1;
                return $result;
            });
    }

    public static function headings(string $slot)
    {
        return [
            'Rank',
            'Player',
            'GoR',
            'Total',
            'Win',
            'Loss',
            'Rate',
            'Status',
         ];
    }
}
