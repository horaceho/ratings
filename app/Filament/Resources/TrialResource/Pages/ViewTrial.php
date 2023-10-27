<?php

namespace App\Filament\Resources\TrialResource\Pages;

use App\Models\Trial;
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
                ->action(fn (Trial $trial) => $trial->doGenerate())
                ->after(fn () => Notification::make()
                    ->title('Generate successfully')
                    ->success()
                    ->send()),
            Actions\EditAction::make(),
        ];
    }
}
