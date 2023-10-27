<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'player',
        'opponent',
        'winner',
        'rating',
        'update',
        'organization',
        'meta',
        'info',
        'player_id',
        'record_id',
        'trial_id',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

    public function trial()
    {
        return $this->belongsTo(Trial::class);
    }
}
