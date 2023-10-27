<?php

namespace App\Filament\Resources\PlayerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('winner')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('winner')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                ->date($format = 'Y-m-d'),
                Tables\Columns\TextColumn::make('player')
                    ->searchable(),
                Tables\Columns\TextColumn::make('opponent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('winner')
                    ->label('🏆')
                    ->color(fn($record) => $record->win ? 'success' : 'default')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('update')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
