<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaketResource\Pages;
use App\Filament\Resources\PaketResource\Pages\ViewPaket;
use App\Filament\Resources\PaketResource\RelationManagers\OrderPaketRelationManager;
use App\Models\Kelas;
use App\Models\Location;
use App\Models\Paket;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PaketResource extends Resource
{
    protected static ?string $model = Paket::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-copy';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        $location = Location::get()->pluck('nama', 'id')->toArray();
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Fieldset::make('Form Paket')
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Paket')
                                    ->string()
                                    ->maxLength(255)
                                    ->required(),
                                Select::make('id_kelas')
                                    ->label('Kelas')
                                    ->preload()
                                    ->searchable()
                                    ->options(Kelas::get()->pluck('nama_kelas', 'id')),
                                Select::make('valid_month')
                                    ->label('Paket Bulanan')
                                    ->options([
                                        '1' => "1 Bulan (30 Hari)",
                                        '3' => '3 Bulan (90 Hari)',
                                        '6' => "6 Bulan (180 Hari)",
                                        '12' => "12 Bulan (360 Hari)",
                                    ])
                                    ->searchable()
                                    ->required(),
                                Select::make('location')
                                    ->preload()
                                    ->searchable()
                                    ->multiple()
                                    ->options($location),
                                TextInput::make('harga')
                                    ->numeric()
                                    ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                        'uang' => fn (Mask $mask) => $mask->numeric()
                                            ->thousandsSeparator('.')
                                            ->decimalSeparator(',')
                                    ])->pattern('Rp.uang'))
                                    ->required(),
                                TextInput::make('price_per_additional')
                                    ->label('Price Per Additional Member')
                                    ->numeric()
                                    ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                        'uang' => fn (Mask $mask) => $mask->numeric()
                                            ->thousandsSeparator('.')
                                            ->decimalSeparator(',')
                                    ])->pattern('Rp.uang'))
                                    ->required(),
                                TextInput::make('signup_fee')
                                    ->label('Signup Fee')
                                    ->numeric()
                                    ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                        'uang' => fn (Mask $mask) => $mask->numeric()
                                            ->thousandsSeparator('.')
                                            ->decimalSeparator(',')
                                    ])->pattern('Rp.uang'))
                                    ->required(),
                                TextInput::make('additional_member')
                                    ->label('Additional Member Limit')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('pro_rata')
                                    ->label('Pro Rata')
                                    ->nullable(),
                                TextInput::make('promote_code'),
                                TextInput::make('diskon')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Toggle::make('is_show_public')
                                    ->label('Tampilkan di publik')
                                    ->required()
                                    ->default(0),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Paket')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('harga_formatted')
                    ->label('Harga')
                    ->sortable(),
                TextColumn::make('harga_formatted')
                    ->label('Harga')
                    ->sortable(),
                TextColumn::make('price_per_additional')
                    ->label('Price Per Additional Member')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('additional_member')
                    ->label('Additional Member Limit'),
                TextColumn::make('signup_fee')
                    ->label('Biaya Pendaftaran')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('pro_rata')
                    ->label('Pro Rata'),
                TextColumn::make('harga_diskon')
                    ->label('Harga Diskon')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('valid_month')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Bulan';
                    })->sortable(),
                TextColumn::make('paketLocation')
                    ->label('Location')
                    ->formatStateUsing(function ($record) {
                        $text = "";
                        foreach ($record->paketLocation as $item) {
                            $text .= $item->location->nama . ", <br>";
                        }

                        echo $text;
                    }),
                ToggleColumn::make('is_show_public')
                    ->label('Tampilkan di public')
                    ->alignCenter()
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->after(function () {
                        activity()
                            ->causedBy(Auth::user())
                            ->log('Menghapus data paket');
                    }),
                ViewAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderPaketRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPakets::route('/'),
            'create' => Pages\CreatePaket::route('/create'),
            'view' => ViewPaket::route('/{record}'),
            'edit' => Pages\EditPaket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class
        ]);
    }
}
