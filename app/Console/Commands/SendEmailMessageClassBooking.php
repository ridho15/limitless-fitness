<?php

namespace App\Console\Commands;

use App\Mail\SendKonfirmasiKehadiranMail;
use App\Models\BookingKelas;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailMessageClassBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-email:class-booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanggalBesok = Carbon::now()->addDays()->isoFormat('YYYY-MM-DD');
        $listBookingKelas = BookingKelas::whereHas('kelasActivity', function($query)use($tanggalBesok){
            $query->whereDate('tanggal', $tanggalBesok);
        })->get();

        foreach($listBookingKelas as $item){
            Mail::to($item->user->email)->send(new SendKonfirmasiKehadiranMail($item));
        }
    }
}
