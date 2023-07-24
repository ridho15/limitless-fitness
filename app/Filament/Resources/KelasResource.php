<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use App\Models\User;
use Closure;
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
                Fieldset::make('Form Kelas')
                    ->schema([
                        Select::make('id_user')
                            ->label('Instruktur')
                            ->searchable()
                            ->preload()
                            ->options($instruktur),
                        TextInput::make('nama_kelas')
                            ->string()
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('jumlah_pertemuan')
                            ->numeric()
                            ->required(),
                        TextInput::make('harga_real')
                            ->name('Harga Asli')
                            ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                'uang' => fn (Mask $mask) => $mask->numeric()
                                    ->thousandsSeparator('.')
                                    ->decimalSeparator(',')
                            ])->pattern('Rp.uang'))
                            ->numeric()
                            ->required(),
                        TextInput::make('diskon')
                            ->afterStateUpdated(function (Closure $set, Closure $get, $state) {
                                $harga = $get('harga_real') - ($get('harga_real') * $state / 100);
                                $set('harga', $harga);
                            })->numeric()
                            ->reactive()
                            ->required()
                            ->maxValue(100)
                            ->helperText('Masukkan persentase diskon'),
                        TextInput::make('harga')
                            ->disabled()
                            ->prefix('Rp.')
                            ->label('Harga'),
                        TextInput::make('min_customer')
                            ->label('Minimal Peserta')
                            ->numeric()
                            ->required(),
                        TextInput::make('max_customer')
                            ->label('Maximal Peserta')
                            ->numeric()
                            ->required(),
                        Textarea::make('keterangan')
                            ->nullable()
                            ->string(),
                        TextInput::make('expired')
                            ->label('Tanggal Waktu Habis (Hari)')
                            ->numeric()
                            ->helperText('Masukkan jumlah hari berlaku paket kelas')
                            ->minValue(0)
                            ->required(),
                        FileUpload::make('foto')
                            ->label('Gambar')
                            ->image()
                            ->maxSize(2048)
                            ->nullable(),
                        Toggle::make('is_active')
                            ->label('Status Aktif Kelas')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                ->height(100)
                ->width(200),
                TextColumn::make('nama_kelas')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Instruktur')
                    ->searchable(),
                TextColumn::make('harga_real')
                    ->label('Harga Asli')
                    ->formatStateUsing(fn ($state) => 'Rp.' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('diskon')
                    ->label('Diskon')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),
                TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('min_customer')
                    ->formatStateUsing(fn ($state) => $state . ' Orang')
                    ->label('Minimal Peserta')
                    ->sortable(),
                TextColumn::make('max_customer')
                    ->label('Maximal Peserta')
                    ->formatStateUsing(fn ($state) => $state . ' Orang')
                    ->sortable(),
                TextColumn::make('status_aktif')
                    ->label('Status Aktif Kelas'),
                TextColumn::make('expired_formatted')
                    ->sortable(),
                TextColumn::make('keterangan'),

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
            //
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
