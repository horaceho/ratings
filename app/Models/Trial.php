<?php

namespace App\Models;

use App\Models\Traits\HasCreatedBy;
use App\Models\Traits\HasUpdatedBy;
use App\Services\ResultService;
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

    public function doGenerate()
    {
        $this->results()->delete();
        ResultService::generate($this);
    }
}
