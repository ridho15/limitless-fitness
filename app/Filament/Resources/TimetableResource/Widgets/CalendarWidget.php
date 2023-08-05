<?php

namespace App\Filament\Resources\TimetableResource\Widgets;

use App\Filament\Resources\TimetableResource;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\LocationStudio;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.resources.timetable-resource.widgets.calendar-widget';

    protected string $modalWidth = 'xl';

    protected string $modalLabel = "Timetable";

    public function getViewData(): array
    {
        $kelasActivity = KelasActivity::get();
        $data = [];
        foreach ($kelasActivity as $item) {
            array_push($data, [
                'id' => $item->id,
                'title' => $item->nama_latihan,
                'start' => $item->jam_mulai,
                'nama_latihan' => $item->nama_latihan,
                'id_ruangan' => $item->id_ruangan,
                'id_kelas' => $item->id_kelas,
                'id_user' => $item->id_user,
                'status' => $item->status,
                'level' => $item->level,
                'cancellation_policy' => $item->cancellation_policy,
                'url' => TimetableResource::getUrl('edit', ['record' => $item]),
                'shouldOpenInNewTab' => true,
            ]);
        }

        return $data;
    }

    public function createEvent(array $data): void
    {
        $start = $data['jam_mulai'];
        $end = $data['jam_selesai'];
        $carbonPeriod = CarbonPeriod::since($start)->hours(1)->until($end)->toArray();
        $jam = [];
        foreach ($carbonPeriod as $item) {
            array_push($jam, date('Y-m-d', strtotime($data['start'])) . date(' H:i', strtotime($item)));
        }
        foreach ($jam as $index => $item) {
            KelasActivity::create([
                'id_kelas' => $data['id_kelas'],
                'nama_latihan' => $data['nama_latihan'],
                'id_ruangan' => $data['id_ruangan'],
                'jam_mulai' => $item,
                'id_user' => $data['id_user'],
                'status' => 1,
                'level' => $data['level'],
                'cancellation_policy' => $data['cancellation_policy']
            ]);
        }

        $this->refreshEvents();
        $this->redirect(TimetableResource::getUrl('index'));
    }

    protected static function getCreateEventFormSchema(): array
    {
        return [
            Grid::make('Form Timetable')
                ->schema([
                    TextInput::make('nama_latihan')
                        ->string()
                        ->required()
                        ->maxLength(255),
                    Select::make('id_kelas')
                        ->label('Kelas')
                        ->preload()
                        ->searchable()
                        ->options(Kelas::get()->pluck('nama_kelas', 'id'))
                        ->required(),
                    Select::make('id_ruangan')
                        ->label('Ruangan')
                        ->preload()
                        ->searchable()
                        ->options(Ruangan::get()->pluck('nama_ruangan', 'id'))
                        ->required(),
                    Select::make('level')
                        ->required()
                        ->options([
                            '0' => 'Open to all',
                            '1' => 'Intermediate',
                            '2' => 'Beginer',
                            '3' => 'Advanced'
                        ]),
                    Select::make('id_user')
                        ->label('Instruktur')
                        ->preload()
                        ->searchable()
                        ->options(User::whereHas('roles', function ($query) {
                            $query->where('name', 'Instruktur');
                        })->get()->pluck('name', 'id'))
                        ->required(),
                    Select::make('status')
                        ->searchable()
                        ->required()
                        ->preload()
                        ->options([
                            '0' => 'Dibatalkan',
                            '1' => 'Set',
                            '2' => 'Sedang berlangsung',
                            '3' => 'Sudah lewat',
                        ])
                        ->hidden()
                        ->default(1),
                    DatePicker::make('start')
                        ->required(),
                    TimePicker::make('jam_mulai')
                        ->label('Jam Mulai')
                        ->required(),
                    TimePicker::make('jam_selesai')
                        ->label('Jam Selesai')
                        ->required(),
                    Textarea::make('cancellation_policy')
                        ->label('Terms and Conditions')
                        ->string()
                        ->required(),
                ])->columns(2)
        ];
    }
}
