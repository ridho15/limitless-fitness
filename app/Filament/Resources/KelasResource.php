<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers\KelasActivityRelationManager;
use App\Filament\Resources\KelasResource\RelationManagers\KelasImageRelationManager;
use App\Models\Kelas;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

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
                                Select::make('level')
                                    ->options([
                                        '0' => 'Semua level',
                                        '1' => 'intermediate',
                                        '2' => 'Beginer',
                                        '3' => 'Advanced',
                                    ])
                                    ->required(),
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
                                    ])->required(),
                                Select::make('category')
                                    ->preload()
                                    ->options([
                                        '1' => 'Private',
                                        '2' => 'Group',
                                    ])->required(),
                                TextInput::make('bawaan')
                                    ->string()
                                    ->nullable()
                                    ->helperText("Untuk pemisah dalam bawaan adalah koma ','"),
                                Textarea::make('keterangan')
                                    ->nullable()
                                    ->string(),
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
                    ->label('Deskripsi'),
                TextColumn::make('duration')
                    ->label('Lama Latihan')
                    ->formatStateUsing(fn ($state) => $state . ' Menit'),
                TextColumn::make('level')
                    ->formatStateUsing(function ($state) {
                        if ($state == 0) {
                            return 'Open to all';
                        } elseif ($state == 1) {
                            return 'Intermediate';
                        } elseif ($state == 2) {
                            return 'Beginner';
                        } elseif ($state == 3) {
                            return 'Advance';
                        } else {
                            return '';
                        }
                    }),
                TextColumn::make('bawaan'),
                TextColumn::make('type')
                    ->formatStateUsing(function ($state) {
                        if ($state == 1) {
                            return 'Online';
                        } elseif ($state == 2) {
                            return 'Onsite';
                        }
                    }),
                TextColumn::make('category')
                    ->formatStateUsing(function ($state) {
                        if ($state == 1) {
                            return 'Private';
                        } elseif ($state == 2) {
                            return 'Group';
                        }
                    })

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
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            KelasImageRelationManager::class,
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
}
