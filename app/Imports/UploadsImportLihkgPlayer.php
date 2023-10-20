<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UploadsImportLihkgPlayer implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    }
}
