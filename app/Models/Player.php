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
        'remark',
        'upload_id',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];
}
