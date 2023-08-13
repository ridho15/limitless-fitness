<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingKelasResource\Pages;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BookingKelasResource extends Resource
{
    protected static ?string $model = BookingKelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-color-swatch';

    protected static ?string $navigationLabel = 'Kehadiran';

    protected static ?string $pluralModelLabel = 'Kehadiran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make()
                            ->schema([
                                Select::make('id_user')
                                    ->label('Customer')
                                    ->preload()
                                    ->searchable()
                                    ->options(User::whereHas('roles', function ($query) {
                                        $query->where('name', 'Customer');
                                    })->get()->pluck('name', 'id')),
                                Select::make('id_kelas_activity')
                                    ->label('Jadwal Kelas')
                                    ->preload()
                                    ->searchable()
                                    ->options(KelasActivity::where(function ($query) {
                                        if (Auth::user()->hasRole('Instruktur')) {
                                            $query->where('id_user', Auth::id());
                                        }
                                    })->where('id_kelas', '!=', null)
                                        ->where('id_location_studio', '!=', null)
                                        ->whereDate('jam_mulai', '>=', now())
                                        ->get()->pluck('simple_detail', 'id')),
                                Radio::make('status_kehadiran')
                                    ->options([
                                        '' => 'Belum di ketahui',
                                        '1' => 'Hadir',
                                        '2' => 'Izin',
                                        '0' => 'Tidak hadir'
                                    ]),
                                Textarea::make('keterangan')
                                    ->nullable(),
                                Toggle::make('is_send_email')
                                    ->label('Send Email')
                                    ->nullable()
                            ])
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::user()->hasRole('Instruktur')) {
            return parent::getEloquentQuery()->whereHas('kelasActivity', function ($query) {
                $query->where('id_user', Auth::id());
            });
        } else {
            return parent::getEloquentQuery();
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Customer')
                    ->searchable(),
                TextColumn::make('kelasActivity.nama_latihan')
                    ->label('Kelas')
                    ->url(function ($record) {
                        return InstrukturJadwalResource::getUrl('view', ['record' => $record->kelasActivity]);
                    }),
                TextColumn::make('kelasActivity.tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    })
                    ->label('Tanggal')
                    ->sortable(),
                TextColumn::make('kelasActivity.jam_mulai')
                    ->label('Jam Mulai')
                    ->sortable(),
                TextColumn::make('status_kehadiran_formatted')
                    ->label('Status Kehadiran')
                    ->sortable(),
                ToggleColumn::make('is_send_email')
                    ->label('Send Email'),
                TextColumn::make('keterangan')
                    ->searchable(),
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListBookingKelas::route('/'),
            'create' => Pages\CreateBookingKelas::route('/create'),
            'edit' => Pages\EditBookingKelas::route('/{record}/edit'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (Auth::user()->hasRole(['Instruktur', 'Admin'])) {
            return false;
        }

        return true;
    }
}
