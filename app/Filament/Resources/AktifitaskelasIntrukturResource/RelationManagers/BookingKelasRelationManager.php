<?php

namespace App\Filament\Resources\AktifitaskelasIntrukturResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Yepsua\Filament\Forms\Components\Rating;
use Yepsua\Filament\Tables\Components\RatingColumn;

class BookingKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'BookingKelas';

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('review')
                    ->nullable()
                    ->string()
                    ->label('Review latihan'),
                Rating::make('rating')
                    ->label('Rating Instruktur')
                    ->max(5)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('review')
                    ->label('Review Latihan')
                    ->searchable(),
                RatingColumn::make('rating')
                    ->label('Rating Instruktur')
                    ->sortable()

            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->hidden(function ($record) {
                        if ($record->rating == null || $record->review == null) {
                            return false;
                        }

                        return true;
                    })
            ])
            ->bulkActions([]);
    }
}
