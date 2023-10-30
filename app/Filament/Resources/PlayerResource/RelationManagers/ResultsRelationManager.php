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
                    ->color(fn ($record): string => $record->pl_result > 0.0 ? 'success' : 'default' )
                    ->searchable(),
                Tables\Columns\TextColumn::make('pl_rating')
                    ->label('')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('pl_update')
                    ->label('')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('pl_change')
                    ->label('')
                    ->color(fn ($record): string => $record->pl_change >= 0.0 ? 'default' : 'danger' )
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('opponent')
                    ->color(fn ($record): string => $record->op_result > 0.0 ? 'success' : 'default' )
                    ->searchable(),
                Tables\Columns\TextColumn::make('op_rating')
                    ->label('')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('op_update')
                    ->label('')
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('op_change')
                    ->label('')
                    ->color(fn ($record): string => $record->op_change >= 0.0 ? 'default' : 'danger' )
                    ->numeric(
                        decimalPlaces: 3,
                        decimalSeparator: '.',
                        thousandsSeparator: '',
                    )
                    ->alignment(Alignment::End),
                Tables\Columns\TextColumn::make('winner')
                    ->label('🏆')
                    ->color('success')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slot'),
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
