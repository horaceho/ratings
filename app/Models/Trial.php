<?php

namespace App\Models;

use App\Models\Traits\HasCreatedBy;
use App\Models\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trial extends Model
{
    use HasCreatedBy;
    use HasUpdatedBy;
    use HasFactory;

    protected $fillable = [
        'algorithm',
        'organization',
        'match',
        'group',
        'from',
        'till',
        'handicap',
        'rank_lo',
        'rank_hi',
        'slot',
        'meta',
        'info',
        'remark',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function getRatingHiAttribute()
    {
        return config('ratings.ranks')[$this->rank_hi] ?? (integer) $this->rank_hi ?? config('ratings.ranks.hi');
    }

    public function getRatingLoAttribute()
    {
        return config('ratings.ranks')[$this->rank_lo] ?? (integer) $this->rank_lo ?? config('ratings.ranks.lo');
    }
}
