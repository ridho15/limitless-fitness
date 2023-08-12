<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Filament\Resources\JurnalResource\RelationManagers;
use App\Models\Jurnal;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Jurnal')
                    ->schema([
                        Select::make('id_parent')
                            ->label('With Parent')
                            ->nullable()
                            ->options(Jurnal::get()->pluck('nama', 'id'))
                            ->helperText("Parent dipilih jika pengeluaran dari biaya parent"),
                        TextInput::make('nama')
                            ->label('Nama Pembayaran')
                            ->string()
                            ->required(),
                        TextInput::make('jumlah_uang')
                            ->numeric()
                            ->mask(fn (Mask $mask) => $mask->patternBlocks([
                                'uang' => fn (Mask $mask) => $mask->numeric()
                                    ->thousandsSeparator('.')
                                    ->decimalSeparator(',')
                            ])->pattern('Rp.uang'))
                            ->disabledOn('edit')
                            ->required(),
                        Radio::make('tipe')
                            ->required()
                            ->options([
                                '1' => 'Pemasukkan',
                                '0' => 'Pengeluaran'
                            ])->label('Tipe'),
                        FileUpload::make('struk')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->nullable(),
                        DateTimePicker::make('tanggal')
                            ->label('Tanggal Pembayaran')
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->dateTime('D, d M Y')
                    ->sortable(),
                TextColumn::make('nama')
                    ->color(function ($record) {
                        if ($record->tipe == '0') {
                            return 'danger';
                        }
                    })
                    ->searchable(),
                TextColumn::make('parent.nama')
                    ->searchable(),
                TextColumn::make('debit')
                    ->label('Debit')
                    ->formatStateUsing(function ($record) {
                        if ($record->tipe == 1) {
                            return 'Rp.' . number_format($record->jumlah_uang, 0, ',', '.');
                        }
                    }),
                TextColumn::make('kredit')
                    ->label('Kredit')
                    ->formatStateUsing(function ($record) {
                        if ($record->tipe == '0') {
                            return 'Rp.' . number_format($record->jumlah_uang, 0, ',', '.');
                        }
                    })->color('danger'),
                TextColumn::make('jumlah_uang')
                    ->label('Saldo')
                    ->formatStateUsing(function ($record) {
                        $saldo = $record->jumlah_uang;
                        if ($record->parent) {
                            $pengeluaran = 0;
                            foreach ($record->parent->children as $item) {
                                if ($item->tipe == '0') {
                                    $pengeluaran += $item->jumlah_uang;
                                }
                            }
                            $saldo = $record->parent->jumlah_uang - $pengeluaran;
                        }
                        return 'Rp.' . number_format($saldo, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->toggleable()
                    ->searchable(),
                ImageColumn::make('struk')
                    ->hidden(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make()
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
            'index' => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit' => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}
