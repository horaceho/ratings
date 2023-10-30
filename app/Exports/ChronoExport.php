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
                'pl_rating',
                'pl_update',
                'pl_change',
                'opponent',
                'op_rating',
                'op_update',
                'op_change',
                'winner',
            ]);
        });
    }

    public function headings(): array
    {
        return [
           'Date',
           'Player',
           'Pl Rating',
           'Pl Update',
           'Pl Change',
           'Opponent',
           'Op Rating',
           'Op Update',
           'Op Change',
           'Winner',
        ];
    }
}
