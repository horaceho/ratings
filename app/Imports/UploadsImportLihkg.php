<?php

namespace App\Imports;

use App\Models\Upload;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UploadsImportLihkg implements WithMultipleSheets
{
    protected Upload $upload;

    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    public function sheets(): array
    {
        return [
            0 => new UploadsImportLihkgRecord($this->upload),
            1 => new UploadsImportLihkgPlayer($this->upload),
        ];
    }
}
