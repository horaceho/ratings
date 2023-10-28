<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Services\PlayerService;
use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPlayers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Copy GoR')
                ->label('Copy GoR')
                ->requiresConfirmation()
                ->modalHeading('Copy GoR')
                ->modalDescription('Confirm to copy GoR for all players?')
                ->modalSubmitActionLabel('Yes, copy them!')
                ->icon('heroicon-o-fire')
                ->form([
                    Forms\Components\Select::make('from')->required()
                        ->options(config('ratings.players.slot.options'))
                        ->required(),
                    Forms\Components\TextInput::make('to')
                        ->default('gor')
                        ->in(collect(config('ratings.players.slot.options'))->keys()->push('gor')->toArray())
                        ->required(),
                ])
                ->action(function (array $data) {
                    PlayerService::copyGoR($data['from'], $data['to']);
                })
                ->after(fn () => Notification::make()
                    ->title('Copy successfully')
                    ->success()
                    ->send()),
            Actions\CreateAction::make(),
        ];
    }
}
