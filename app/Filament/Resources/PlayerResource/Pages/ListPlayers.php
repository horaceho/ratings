<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Services\ResultService;
use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPlayers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Reset GoR')
            ->label('Reset GoR')
            ->requiresConfirmation()
            ->modalHeading('Reset GoR')
            ->modalDescription('Confirm to reset GoR of all players?')
            ->modalSubmitActionLabel('Yes, reset them!')
            ->icon('heroicon-o-fire')
            ->action(fn () => ResultService::resetPlayersGoR())
            ->after(fn () => Notification::make()
                ->title('Generate successfully')
                ->success()
                ->send()),
            Actions\CreateAction::make(),
        ];
    }
}
