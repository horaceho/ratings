<?php

namespace App\Imports;

use App\Models\Record;
use App\Models\Upload;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class UploadsImportLihkgRecord implements ToCollection, WithHeadingRow, WithCalculatedFormulas
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
            $date = $row['date'];
            $black = $row['black'] ?? $row['player1'] ?? $row['player_1'] ?? null;
            $white = $row['white'] ?? $row['player2'] ?? $row['player_2'] ?? null;
            $winner = $row['winner_name'];
            $organization = $row['organization'] ?? 'LIHKG';
            $match = $row['match'] ?? $row['match_type'] ?? null;
            $group = $row['group'] ?? $row['season'] ?? null;
            $round = $row['round'] ?? null;
            $short = Str::slug("#{$organization} #{$match} #{$group}");
            $difference = $row['difference'] ?? 0.0;

            if (empty($date)  ||
                empty($black) ||
                empty($white) ||
                empty($match) ||
                empty($round)
            ) {
                continue;
            }

            $record = Record::updateOrCreate([
                'date' => ExcelDate::excelToDateTimeObject($date),
                'black' => $black,
                'white' => $white,
                'match' => $match,
                'group' => $group,
                'round' => $round,
            ], [
                'winner' => $winner,
                'result' => $row['result'] ?? '',
                'handicap' => $difference,
                'organization' => $organization,
                'short' => $short,
                'link' => $row['ogs_link'] ?? $row['link'] ?? '',
                'team' => $row['team'] ?? '',
                'remark' => $row['remark'] ?? '',
                'upload_id' => $this->upload->id,
            ]);
        }
    }
}
