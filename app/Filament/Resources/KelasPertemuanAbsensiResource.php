<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasPertemuanAbsensiResource\Pages;
use App\Filament\Resources\KelasPertemuanAbsensiResource\RelationManagers;
use App\Models\CustomerKelas;
use App\Models\Kelas;
use App\Models\KelasPertamuan;
use App\Models\KelasPertemuanAbsensi;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class KelasPertemuanAbsensiResource extends Resource
{
    protected static ?string $model = KelasPertemuanAbsensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Kelas Pertamuan (Absensi)';

    protected static ?string $pluralModelLabel = 'Kelas Pertamuan (Absensi)';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Form Absensi')
                    ->schema([
                        Select::make('id_kelas')
                            ->label('Pilih Kelas')
                            ->preload()
                            ->searchable()
                            ->options(Kelas::get()->pluck('nama_kelas', 'id'))
                            ->required(),
                        Select::make('id_kelas_pertemuan')
                            ->label('Pilih Pertemuan')
                            ->preload()
                            ->searchable()
                            ->options(function (Closure $get) {
                                return KelasPertamuan::where('id_kelas', $get('id_kelas'))->get()->pluck('datetime_formatted', 'id');
                            })
                            ->required(),
                        Select::make('id_user')
                            ->label('Peserta')
                            ->preload()
                            ->searchable()
                            ->options(function (Closure $get) {
                                $customerKelas = CustomerKelas::with('user')->where('id_kelas', $get('id_kelas'))
                                    ->get()->pluck('user.name', 'id_user');
                                return $customerKelas;
                            })->required(),
                        DateTimePicker::make('waktu_masuk')
                            ->label('Waktu Masuk')
                            ->nullable(),
                        Radio::make('status')
                            ->label('Status Absensi')
                            ->required()
                            ->options([
                                '0' => 'Tidak Hadir',
                                '1' => 'Hadir / Masuk',
                                '2' => 'Izin'
                            ]),
                        Textarea::make('keterangan')
                            ->string()
                            ->nullable()

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelasPertemuan.kelas.nama_kelas')
                    ->searchable(),
                TextColumn::make('kelasPertemuan.kelas.user.name')
                    ->searchable(),
                TextColumn::make('waktu_masuk_formatted')
                    ->sortable(),
                TextColumn::make('status_formatted'),
                TextColumn::make('keterangan')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $kelasPertemuan = KelasPertamuan::find($data['id_kelas_pertemuan']);
                        $data['id_kelas'] = $kelasPertemuan->id_kelas;
                        return $data;
                    }),
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
            'index' => Pages\ListKelasPertemuanAbsensis::route('/'),
            'create' => Pages\CreateKelasPertemuanAbsensi::route('/create'),
            'edit' => Pages\EditKelasPertemuanAbsensi::route('/{record}/edit'),
        ];
    }
}
