<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasRatingResource\Pages;
use App\Filament\Resources\KelasRatingResource\RelationManagers;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\KelasRating;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Yepsua\Filament\Forms\Components\Rating;
use Yepsua\Filament\Tables\Components\RatingColumn;

class KelasRatingResource extends Resource
{
    protected static ?string $model = KelasRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('form Rating')
                            ->schema([
                                Select::make('id_user')
                                    ->label('Customer')
                                    ->searchable()
                                    ->preload()
                                    ->options(User::whereHas('roles', function ($query) {
                                        $query->where('name', 'Customer');
                                    })->select(['name', 'id'])->get()->pluck('name', 'id'))
                                    ->required(),
                                Select::make('id_kelas_activity')
                                    ->label('Kelas Activity')
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelUsing(function ($value): string {
                                        $kelasActivity = KelasActivity::find($value);
                                        return $kelasActivity->kelas->nama_kelas . ' ' . $kelasActivity->jam_latihan;
                                    })
                                    ->required(),
                                Textarea::make('comment')
                                    ->label('Review')
                                    ->string(),
                                Rating::make('rating')
                                    ->label('Rating')
                                    ->required(),

                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('id_kelas_activity')
                    ->label('Kelas')
                    ->formatStateUsing(function($state){
                        $kelasActivity = KelasActivity::find($state);
                        if ($kelasActivity) {
                            return $kelasActivity->kelas->nama_kelas . ' ' . $kelasActivity->jam_latihan_formatted;
                        }

                        return null;
                    })
                    ->searchable(),
                TextColumn::make('comment')
                    ->label('Review')
                    ->searchable(),
                RatingColumn::make('rating'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                DeleteBulkAction::make()
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
            'index' => Pages\ListKelasRatings::route('/'),
            'create' => Pages\CreateKelasRating::route('/create'),
            'edit' => Pages\EditKelasRating::route('/{record}/edit'),
        ];
    }
}
