<?php

namespace App\Imports;

use App\Models\Player;
use App\Models\Upload;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UploadsImportLihkgPlayer implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    protected Upload $upload;

    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $name = $row['player'];
            $init = $row['initial_rank'];

            if (empty($name)) {
                continue;
            }

            $player = Player::updateOrCreate([
                'name' => $name,
            ], [
                'init' => $init,
                'upload_id' => $this->upload->id,
            ]);
        }
    }
}
