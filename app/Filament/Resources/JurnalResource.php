<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Filament\Resources\JurnalResource\RelationManagers;
use App\Models\Jurnal;
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
                        Select::make('id_coa')
                            ->relationship('coa', 'nomor')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                TextColumn::make('coa.nomor')
                    ->label('Coa')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('tipe_formatted'),
                TextColumn::make('jumlah_uang')
                    ->money('IDR')
                    ->sortable(),
                ImageColumn::make('struk'),
                TextColumn::make('tanggal')
                    ->dateTime('D, d M Y')
                    ->sortable()
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
