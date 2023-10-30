<?php

namespace App\Exports;

use App\Models\Trial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChronoExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected Trial $trial;

    public function __construct(Trial $trial)
    {
        $this->trial = $trial;
    }

    public function collection()
    {
        return $this->trial->results()->get()->map(function ($result) {
            return $result->only([
                'date',
                'player',
                'opponent',
                'winner',
                'pl_rating',
                'pl_update',
                'change',
            ]);
        });
    }

    public function headings(): array
    {
        return [
           'Date',
           'Player',
           'Opponent',
           'Winner',
           'Rating',
           'Update',
           'Change',
        ];
    }
}
