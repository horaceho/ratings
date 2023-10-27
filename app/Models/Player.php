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

    public function getRatingAttribute()
    {
        if ($this->gor > 0.0) {
            return $this->gor;
        }

        return config('ratings.ranks')[$this->init] ?? 2100.0;
    }
}
