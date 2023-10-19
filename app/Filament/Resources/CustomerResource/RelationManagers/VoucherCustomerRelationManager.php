<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherCustomerRelationManager extends RelationManager
{
    protected static string $relationship = 'voucherCustomer';

    protected static ?string $recordTitleAttribute = 'id_customer';

    protected static ?string $modelLabel = 'Tambah Voucher';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_voucher')
                    ->searchable()
                    ->preload()
                    ->relationship('voucher', 'nama')
                    ->required(),
                DateTimePicker::make('expired')
                    ->label('Expired Date')
                    ->required(),
                DateTimePicker::make('tanggal_digunakan')
                    ->label('Tanggal Digunakan'),
                Radio::make('status')
                    ->label('Status Voucher')
                    ->options([
                        '0' => 'Tidak aktif',
                        '1' => 'Aktif'
                    ])->required(),
                Radio::make('status_digunakan')
                    ->label('Status Digunakan')
                    ->options([
                        '0' => 'Belum digunakan',
                        '1' => 'Sudah digunakan'
                    ])->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voucher.nama')
                    ->label('Nama Voucher')
                    ->searchable(),
                TextColumn::make('expired')
                    ->label('Expired Date'),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary',
                        'success' => 1
                    ])->enum([
                        0 => 'Tidak Aktif',
                        1 => 'Aktif'
                    ]),
                BadgeColumn::make('status_digunakan')
                    ->colors([
                        'success',
                        'secondary' => 1
                    ])->enum([
                        0 => 'Belum digunakan',
                        1 => 'Sudah digunakan'
                    ]),
                TextColumn::make('tanggal_digunakan')
                    ->label('Tanggal Digunakan')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Voucher')
                    ->icon('heroicon-o-plus-circle'),
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
