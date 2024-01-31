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
        return $this->trial->results()->with('record')->get()->map(function ($result) {
            return [
                str_replace(' 00:00:00', '', $result['date']),
                $result['player'],
                $result['pl_rating'],
                $result['pl_update'],
                $result['pl_change'],
                $result['opponent'],
                $result['op_rating'],
                $result['op_update'],
                $result['op_change'],
                $result['winner'],
                $result['record']['match'],
                $result['record']['group'],
                $result['record']['round'],
            ];
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
           'Match',
           'Group',
           'Round',
        ];
    }
}
