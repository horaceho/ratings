<?php

namespace App\Models;

use App\Models\Traits\HasCreatedBy;
use App\Models\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasCreatedBy;
    use HasUpdatedBy;
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'alias',
        'other',
        'init',
        'rank',
        'gor',
        's0',
        's1',
        's2',
        's3',
        's4',
        's5',
        's6',
        's7',
        's8',
        's9',
        'remark',
        'upload_id',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];

    public function results()
    {
        return $this->hasMany(Result::class, 'entrant_id');
    }

    public function rating(string $slot)
    {
        if ($this->getAttribute($slot) > 0.0) {
            return $this->getAttribute($slot);
        }

        return config('ratings.ranks')[$this->init] ?? (integer) $this->init;
    }
}
