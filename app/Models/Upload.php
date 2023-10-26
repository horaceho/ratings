<?php

namespace App\Models;

use App\Imports\UploadsImportLihkg;
use App\Models\Traits\HasCreatedBy;
use App\Models\Traits\HasUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Upload extends Model
{
    use HasCreatedBy;
    use HasUpdatedBy;
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'filename',
        'original',
    ];

    protected $casts = [
        'meta' => 'json',
        'info' => 'json',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function doImport()
    {
        Excel::import(new UploadsImportLihkg($this), $this->filename, 'uploads');
    }

    public function doDeletePlayers()
    {
        $this->players()->delete();
    }

    public function doDeleteRecords()
    {
        $this->records()->delete();
    }
}
