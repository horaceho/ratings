<?php

namespace App\Exports;


use App\Models\Trial;
use App\Services\PlayerService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class RatingExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    protected Trial $trial;

    public function __construct(Trial $trial)
    {
        $this->trial = $trial;
    }

    public function collection()
    {
        return PlayerService::rankings($this->trial->slot);
    }

    public function headings(): array
    {
        return PlayerService::headings($this->trial->slot);
    }
}
