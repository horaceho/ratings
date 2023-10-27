<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'entrant_id',
        'opposer_id',
        'record_id',
        'trial_id',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];

    public function entrant()
    {
        return $this->belongsTo(Player::class, 'entrant_id');
    }

    public function opposer()
    {
        return $this->belongsTo(Player::class, 'opposer_id');
    }

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

    public function trial()
    {
        return $this->belongsTo(Trial::class);
    }

    public function getWinAttribute()
    {
        return $this->player === $this->winner ? 1.0 : 0.0;
    }
}
