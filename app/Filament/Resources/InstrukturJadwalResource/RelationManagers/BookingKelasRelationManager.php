<?php

namespace App\Filament\Resources\InstrukturJadwalResource\RelationManagers;

use App\Models\OrderPaket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BookingKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'BookingKelas';

    protected static ?string $recordTitleAttribute = 'id_kelas_activity';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_user')
                    ->label('Peserta')
                    ->preload()
                    ->searchable()
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Customer');
                    })->pluck('name', 'id')),
                Radio::make('status_kehadiran')
                    ->options([
                        '' => 'Belum di ketahui',
                        '1' => 'Hadir',
                        '2' => 'Izin',
                        '0' => 'Tidak hadir'
                    ])->hidden(function ($state) {
                        if (Auth::user()->hasRole('Super Admin')) {
                            return false;
                        } else {
                            return true;
                        }
                    }),
                Toggle::make('is_send_email')
                    ->label('Send Email')
                    ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Peserta')
                    ->searchable(),
                BadgeColumn::make('is_send_email')
                    ->label('Send Email')
                    ->enum([
                        1 => 'Ya',
                        0 => 'Tidak'
                    ])->colors([
                        'primary' => 1,
                        'secondary' => 0
                    ]),
                BadgeColumn::make('status_kehadiran_formatted')
                    ->label('Status Kehadiran'),
                TextColumn::make('keterangan')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                EditAction::make()
                    ->hidden(function () {
                        if (Auth::user()->hasRole('Instruktur')) {
                            return true;
                        }

                        return false;
                    }),
                DeleteAction::make()
                    ->after(function ($record) {
                        $bookingKelas = $record;
                        $orderPaket = OrderPaket::whereHas('paket', function ($query) use ($bookingKelas) {
                            $query->where('id_kelas', $bookingKelas->kelasActivity->id_kelas);
                        })->whereDate('expired_date', '>=', now())
                            ->where('status', 1)
                            ->where('is_active', 1)
                            ->where('id_user', $bookingKelas->id_user)
                            ->first();
                        if ($orderPaket) {
                            $orderPaket->update([
                                'sisa' => $orderPaket->sisa + 1
                            ]);
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
