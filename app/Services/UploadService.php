<?php

namespace App\Services;

use App\Models\Upload;
use App\Imports\UploadsImportLihkg;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public static function import(Upload $upload)
    {
        if (! Storage::disk('uploads')->exists($upload->filename)) {
            return;
        }

        Excel::import(new UploadsImportLihkg($upload), $upload->filename, 'uploads');
    }
}
