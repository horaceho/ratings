<?php

namespace App\Filament\Resources\TrialResource\Pages;

use App\Models\Trial;
use App\Services\ResultService;
use App\Filament\Resources\TrialResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTrial extends ViewRecord
{
    protected static string $resource = TrialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Generate')
                ->requiresConfirmation()
                ->modalHeading('Generate Results')
                ->modalDescription('Confirm to generate results of this Trial?')
                ->modalSubmitActionLabel('Yes, generate them!')
                ->icon('heroicon-o-bolt')
                ->action(fn (Trial $trial) => ResultService::refresh($trial))
                ->after(fn () => Notification::make()
                    ->title('Generate successfully')
                    ->success()
                    ->send()),
            Actions\Action::make('Export Chrono')
                ->requiresConfirmation()
                ->modalHeading('Export Chrono')
                ->modalDescription('Confirm to export chrono of this Trial?')
                ->modalSubmitActionLabel('Yes, export them!')
                ->icon('heroicon-o-bolt')
                ->url(fn (Trial $trial): string => route('export.chrono', ['id' => $trial]),
                    shouldOpenInNewTab: true
                )
                ->after(fn () => Notification::make()
                    ->title('Export successfully')
                    ->success()
                    ->send()),
            Actions\EditAction::make(),
        ];
    }
}
