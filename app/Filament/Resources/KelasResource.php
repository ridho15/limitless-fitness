<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers\KelasActivityRelationManager;
use App\Filament\Resources\KelasResource\RelationManagers\KelasImageRelationManager;
use App\Filament\Resources\KelasResource\RelationManagers\KelasPaketRelationManager;
use App\Filament\Resources\KelasResource\RelationManagers\PaketRelationManager;
use App\Filament\Resources\KelasResource\RelationManagers\PaketRelationManagerRelationManager;
use App\Models\Kelas;
use App\Models\Location;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Auth;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        $instruktur = User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->get()->pluck('name', 'id');
        $location = Location::get()->pluck('nama', 'id')->toArray();
        $location[0] = 'All location';
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
                                    ->options($location),
                                TextInput::make('min_anggota')
                                    ->label('Minimal Peserta')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('max_anggota')
                                    ->label('Maximal Peserta')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('duration')
                                    ->label('Durasi')
                                    ->helperText("Hitungan Menit contoh : 10")
                                    ->numeric()
                                    ->required(),
                                Select::make('type')
                                    ->preload()
                                    ->options([
                                        '1' => 'Online',
                                        '2' => 'Onsite',
                                    ])->default(2)->required(),
                                TextInput::make('bawaan')
                                    ->string()
                                    ->nullable()
                                    ->helperText("Untuk pemisah dalam bawaan adalah koma ','"),
                                Textarea::make('keterangan')
                                    ->nullable()
                                    ->string(),
                                TextInput::make('honor')
                                    ->label('Pembayaran Instruktur')
                                    ->numeric()
                                    ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                        'uang' => fn (Mask $mask) => $mask->numeric()
                                            ->thousandsSeparator('.')
                                            ->decimalSeparator(',')
                                    ])->pattern('Rp.uang'))
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Status Aktif Kelas')
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
                BadgeColumn::make('is_many_times')
                    ->label('Many Times')
                    ->colors([
                        'secondary' => 0,
                        'success' => 1
                    ])->enum([
                        0 => 'Hanya sekali',
                        1 => 'Digunakan berkali kali'
                    ]),
                TextColumn::make('min_anggota')
                    ->formatStateUsing(fn ($state) => $state . ' Orang')
                    ->label('Minimal Peserta')
                    ->sortable(),
                TextColumn::make('max_anggota')
                    ->label('Maximal Peserta')
                    ->formatStateUsing(fn ($state) => $state . ' Orang')
                    ->sortable(),
                TextColumn::make('status_aktif')
                    ->label('Status Aktif Kelas'),
                TextColumn::make('keterangan')
                    ->limit(100)
                    ->label('Deskripsi'),
                TextColumn::make('duration')
                    ->label('Lama Latihan')
                    ->formatStateUsing(fn ($state) => $state . ' Menit'),
                TextColumn::make('bawaan'),
                TextColumn::make('honor')
                    ->label('Pembayaran Insruktur')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })->sortable(),
                TextColumn::make('type_formatted')
                    ->label('Type'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        activity()
                            ->causedBy(Auth::user())
                            ->log('Menghapus data kelas');
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            KelasImageRelationManager::class,
            PaketRelationManager::class,
            KelasActivityRelationManager::class
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
