<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'BookingKelas';

    protected static ?string $recordTitleAttribute = 'id_user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_kelas_activity')
                    ->label('Jadwal Kelas')
                    ->preload()
                    ->searchable()
                    ->options(KelasActivity::get()->pluck('nama_latihan', 'id'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelasActivity.nama_latihan')
                    ->label('Nama Latihan')
                    ->searchable(),
                TextColumn::make('kelasActivity.tanggal')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
                    })
                    ->sortable(),
                TextColumn::make('kelasActivity.jam_mulai')
                    ->label('Jam Mulai')
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
