<?php

namespace App\Models;

use App\Models\Traits\HasCreatedBy;
use App\Models\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasCreatedBy;
    use HasUpdatedBy;
    use HasFactory;

    protected $fillable = [
        'date',
        'black',
        'white',
        'winner',
        'result',
        'handicap',
        'organization',
        'match',
        'group',
        'round',
        'link',
        'team',
        'remark',
        'upload_id',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];
}
