<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers\KelasImageRelationManager;
use App\Filament\Resources\KelasResource\RelationManagers\PaketRelationManager;
use App\Models\Kelas;
use App\Models\Location;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Kelas')
                            ->schema([
                                TextInput::make('nama_kelas')
                                    ->string()
                                    ->maxLength(255)
                                    ->required(),
                                Select::make('id_location')
                                    ->label('Location')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->relationship('location', 'nama')
                                    ->required(),
                                Select::make('id_paket')
                                    ->label('Paket')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->relationship('paket', 'nama', function ($get, Builder $query) {
                                        $query->whereHas('paketLocation', function (Builder $query) use ($get) {
                                            $query->where('id_location', $get('id_location'));
                                        });
                                    })
                                    ->multiple(),
                                TextInput::make('min_anggota')
                                    ->label('Minimal Peserta')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('max_anggota')
                                    ->label('Maximal Peserta')
                                    ->numeric()
                                    ->required(),
                                TimePicker::make('waktu_buka')
                                    ->label('Waktu Buka')
                                    ->helperText("Jam buka setiap hari. Jika kosong kapan pun user bisa checkin")
                                    ->nullable(),
                                TimePicker::make('waktu_tutup')
                                    ->label('Waktu Tutup')
                                    ->helperText("Jam tutup setiap hari. Jika kosong kapan pun user bisa checkin")
                                    ->nullable(),
                                Textarea::make('keterangan')
                                    ->nullable()
                                    ->string(),
                                Select::make('id_user')
                                    ->label('Instruktur')
                                    ->preload()
                                    ->searchable()
                                    ->multiple()
                                    ->relationship('instruktur', 'name', function (Builder $query) {
                                        $query->whereHas('roles', function (Builder $query) {
                                            $query->where('name', 'Instruktur');
                                        });
                                    }),
                                TextInput::make('honor')
                                    ->label('Pembayaran Instruktur')
                                    ->numeric()
                                    ->mask(fn(Mask $mask) => $mask->patternBlocks([
                                        'uang' => fn(Mask $mask) => $mask->numeric()
                                            ->thousandsSeparator('.')
                                            ->decimalSeparator(',')
                                    ])->pattern('Rp.uang'))
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Status Aktif Kelas'),
                                Repeater::make('kelasImage')
                                    ->relationship()
                                    ->schema([
                                        FileUpload::make('image')
                                            ->image()
                                            ->acceptedFileTypes(['image/*'])
                                            ->maxSize(1024)
                                            ->required(),
                                    ])
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->searchable(),
                TextColumn::make('location')
                    ->formatStateUsing(function ($record) {
                        if ($record->id_location == 0 || $record->id_location == null) {
                            return 'All Location';
                        } else {
                            return $record->location->nama;
                        }
                    }),
                TextColumn::make('min_anggota')
                    ->formatStateUsing(fn($state) => $state . ' Orang')
                    ->label('Minimal Peserta')
                    ->sortable(),
                TextColumn::make('max_anggota')
                    ->label('Maximal Peserta')
                    ->formatStateUsing(fn($state) => $state . ' Orang')
                    ->sortable(),
                TextColumn::make('waktu_buka')
                    ->label('Waktu Buka')
                    ->time(),
                TextColumn::make('waktu_tutup')
                    ->label('Waktu Tutup')
                    ->time(),
                BadgeColumn::make('paket.nama')
                    ->label('Paket')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Status Aktif Kelas'),
                TextColumn::make('keterangan')
                    ->limit(100)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Deskripsi'),
                TextColumn::make('honor')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Pembayaran Insruktur')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    DeleteAction::make()
                        ->after(function () {
                            activity()
                                ->causedBy(Auth::user())
                                ->log('Menghapus data kelas');
                        })
                ])
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            KelasImageRelationManager::class,
            PaketRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return true;
        }

        return false;
    }
}
