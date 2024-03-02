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
                                TextInput::make('jumlah_pertemuan')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('valid_day')
                                    ->numeric()
                                    ->required()
                                    ->default(0),
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
                                TextInput::make('promote_code'),
                                TextInput::make('diskon')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Radio::make('is_many_times')
                                    ->label('Many TImes')
                                    ->options([
                                        '1' => 'Yes',
                                        '0' => 'No'
                                    ]),
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
                TextColumn::make('jumlah_pertemuan')
                    ->label('Jumlah Pertemuan')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Pertemuan';
                    })
                    ->sortable(),
                TextColumn::make('harga_formatted')
                    ->label('Harga')
                    ->sortable(),
                TextColumn::make('diskon')
                    ->formatStateUsing(function ($state) {
                        return $state . '%';
                    }),
                TextColumn::make('harga_diskon')
                    ->label('Harga Diskon')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('kelas.honor')
                    ->label('Pembayaran Instruktur')
                    ->formatStateUsing(function ($state) {
                        return 'Rp.' . number_format($state, 0, ',', '.');
                    })->sortable(),
                TextColumn::make('valid_day')
                    ->formatStateUsing(function ($state) {
                        return $state . ' Hari';
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
                BadgeColumn::make('is_many_times')
                    ->label('Many Times')
                    ->colors([
                        'secondary' => 0,
                        'success' => 1
                    ])->enum([
                        0 => 'Hanya sekali',
                        1 => 'Digunakan berkali kali'
                    ]),
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
