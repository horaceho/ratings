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
        'from',
        'till',
        'handicap',
        'rank_lo',
        'rank_hi',
        'meta',
        'info',
        'remark',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];
}
