<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultResource\Pages;
use App\Filament\Resources\ResultResource\RelationManagers;
use App\Models\Result;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResultResource extends Resource
{
    protected static ?string $model = Result::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->native(false)
                    ->format('Y-m-d')
                    ->displayFormat('Y-m-d')
                    ->required(),
                Forms\Components\TextInput::make('winner')
                    ->required(),
                Forms\Components\TextInput::make('player')
                    ->required(),
                Forms\Components\TextInput::make('pl_change')
                    ->label('Change')
                    ->numeric()
                    ->readOnly(),
                Forms\Components\TextInput::make('pl_rating')
                    ->label('Rating')
                    ->numeric()
                    ->inputMode('decimal'),
                Forms\Components\TextInput::make('pl_update')
                    ->label('Update')
                    ->numeric()
                    ->inputMode('decimal'),
                Forms\Components\TextInput::make('opponent')
                    ->required(),
                Forms\Components\TextInput::make('op_change')
                    ->label('Change')
                    ->numeric()
                    ->readOnly(),
                Forms\Components\TextInput::make('op_rating')
                    ->label('Rating')
                    ->numeric()
                    ->inputMode('decimal'),
                Forms\Components\TextInput::make('op_update')
                    ->label('Update')
                    ->numeric()
                    ->inputMode('decimal'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date($format = 'Y-m-d')
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResults::route('/'),
            'create' => Pages\CreateResult::route('/create'),
            'view' => Pages\ViewResult::route('/{record}'),
            'edit' => Pages\EditResult::route('/{record}/edit'),
        ];
    }
}
