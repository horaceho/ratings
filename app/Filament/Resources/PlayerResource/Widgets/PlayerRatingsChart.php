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
            ->select(
                'results.pl_rating',
                'records.date',
                'records.match',
                'records.group',
                'records.round',
            )
            ->join('records', 'records.id', '=', 'results.record_id')
            ->orderBy('records.date')
            ->orderBy('records.match')
            ->orderBy('records.group')
            ->orderBy('records.round')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => $this->record->name,
                    'data' => $results->map(fn($result) => $result->pl_rating),
                ],
            ],
            'labels' => $results->map(fn($result) =>
                substr($result->date, 0, 4).' '.$result->match.' '.$result->group.' '.$result->round),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
