<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrukturJadwalResource\Pages;
use App\Filament\Resources\InstrukturJadwalResource\Pages\ViewInstrukturJadwal;
use App\Filament\Resources\InstrukturJadwalResource\RelationManagers;
use App\Filament\Resources\InstrukturJadwalResource\RelationManagers\BookingKelasRelationManager;
use App\Models\InstrukturJadwal;
use App\Models\KelasActivity;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InstrukturJadwalResource extends Resource
{
    protected static ?string $model = KelasActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $label = "Atur Jadwal";

    protected static ?string $modelLabel = 'Atur Jadwal';

    protected static ?string $pluralModelLabel = 'Atur Jadwal';

    protected static ?string $slug = 'atur-jadwal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Jadwal')
                            ->schema([
                                DatePicker::make('tanggal')
                                    ->label('Tanggal Avaiable')
                                    ->disabledOn('edit')
                                    ->required(),
                                TimePicker::make('jam_mulai')
                                    ->label('Waktu Mulai')
                                    ->disabledOn('edit')
                                    ->required(),
                                TimePicker::make('jam_selesai')
                                    ->label('Waktu Selesai')
                                    ->disabledOn('edit')
                                    ->hiddenOn(['edit', 'view'])
                                    ->required(),
                                TextInput::make('nama_latihan')
                                    ->label('Nama Latihan')
                                    ->disabledOn('create'),
                                Select::make('level')
                                    ->disabledOn('create')
                                    ->options([
                                        '0' => 'Semua Level',
                                        '1' => 'Intermediate',
                                        '2' => 'Beginer',
                                        '3' => 'Advanced'
                                    ])
                            ])
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::user()->hasRole('Instruktur')) {
            return parent::getEloquentQuery()->where('id_user', Auth::id());
        } else {
            return parent::getEloquentQuery();
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_latihan')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('ruangan.locationStudio.nama')
                    ->label('Lokasi Studio')
                    ->searchable(),
                TextColumn::make('ruangan.nama_ruangan')
                    ->label('Ruangan')
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('DD MMMM YYYY');
                    })
                    ->sortable(),
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->locale('id')->isoFormat('HH:mm');
                    })
                    ->sortable(),
                TextColumn::make('status_aktivitas_kelas')
                    ->label('Status Jadwal')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingKelasRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstrukturJadwals::route('/'),
            'create' => Pages\CreateInstrukturJadwal::route('/create'),
            'view' => ViewInstrukturJadwal::route('/{record}'),
            'edit' => Pages\EditInstrukturJadwal::route('/{record}/edit'),
        ];
    }
}
