<?php

namespace App\Exports;

use App\Models\Player;
use App\Models\Trial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RatingExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected Trial $trial;

    public function __construct(Trial $trial)
    {
        $this->trial = $trial;
    }

    public function collection()
    {
        $slot = $this->trial->slot;
        return Player::orderBy($slot, 'desc')->get()->map(function ($result) use ($slot) {
            return $result->only([
                'id',
                'name',
                $slot,
                'win',
                'loss',
                'rate',
                'status',
            ]);
        });
    }

    public function headings(): array
    {
        return [
           'ID',
           'Name',
           'GoR',
           'Win',
           'Loss',
           'Rate',
           'Status',
        ];
    }
}
