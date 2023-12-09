<?php

namespace App\Filament\Resources\InstrukturOmsetResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'BookingKelas';

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('status_kehadiran')
                    ->label('Status Kehadiran')
                    ->formatStateUsing(function ($state) {
                        if ($state == null) {
                            return 'Belum dikonfirmasi';
                        } elseif ($state == 0) {
                            return 'Tidak Hadir';
                        } elseif ($state == 1) {
                            return 'Hadir';
                        } elseif ($state == 2) {
                            return 'Izin';
                        }
                    }),
                TextColumn::make('created_at')
                    ->label('Jadwal Booking')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY H:i');
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
