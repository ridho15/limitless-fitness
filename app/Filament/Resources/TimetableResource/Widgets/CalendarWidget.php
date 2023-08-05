<?php

namespace App\Filament\Resources\TimetableResource\Widgets;

use App\Filament\Resources\TimetableResource;
use App\Models\Kelas;
use App\Models\KelasActivity;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Illuminate\Support\Facades\Auth;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.resources.timetable-resource.widgets.calendar-widget';

    protected string $modalWidth = 'xl';

    protected string $modalLabel = "Timetable";

    public function getViewData(): array
    {
        if (Auth::user()->hasRole('Instruktur')) {
            $kelasActivity = KelasActivity::where('id_user', Auth::id())->get();
        }else{
            $kelasActivity = KelasActivity::get();
        }
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
                'id_user' => Auth::user()->hasRole('Instruktur') ? Auth::id() : $data['id_user'],
                'status' => 1,
                'level' => $data['level'],
                'cancellation_policy' => '1. All payments must be received before your session begins.
                2. Booking: All sessions must be booked in advance. Please
                call our studio or use our online booking system to schedule
                your session.
                3. Attendance: Please arrive on time for your session. If you
                arrive late, the session will still end at the scheduled time, and
                you will still be charged the full session rate.
                4. Attire: Please wear comfortable clothing that allows you to
                move freely. We recommend wearing grip socks for hygiene
                easons.
                5. Equipment: Our studio provides mats, blocks, straps, and
                other necessary equipment. Please do not bring your own
                equipment unless approved by our instructors.
                6. Health and Safety: Please inform your instructor of any
                injuries or health conditions before starting your session. If you
                feel any discomfort or pain during the session, inform your
                instructor immediately.
                7. If class is less than 2 pax in 1 session, studio is able to
                cancel the class 3 hours before conduct,
                8. Reschedule and Refunds:
                a. All sessions purchased are non-refundable.
                b. Rescheduling of a session must be done at least 24
                 hours before the scheduled session time.
                c. Late cancellations, Reschedule or no-shows will result in a
                 penalty made less than 24 hours before the scheduled session
                 time will result in the forfeit of that session.
                d. Exceptions to these rules will be made in the event of
                 sudden illness or emergency situations, and will be
                 handled on a case-by-case basis.'
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
                        ->hidden(Auth::user()->hasRole('Instruktur') ? true : false)
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
                ])->columns(1)
        ];
    }
}
