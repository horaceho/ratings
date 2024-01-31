<?php

namespace App\Filament\Resources\PlayerResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class PlayerRatingsChart extends ChartWidget
{
    public ?Model $record = null;
    protected static ?string $heading = '';

    protected function getData(): array
    {
     // self::$heading = $this->record->name;
        $results = $this->record->results()
            ->with('record')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => $this->record->name,
                    'data' => $results->map(fn($result) => $result->pl_rating),
                ],
            ],
            'labels' => $results->map(fn($result) =>
                substr($result->record->date, 0, 4).' '.$result->record->match.' '.$result->record->round),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
