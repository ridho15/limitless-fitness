<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingKelas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'booking_kelas';
    protected $fillable = [
        'id_user',
        'id_kelas_activity',
        'is_send_email',
        'status_kehadiran',
        'keterangan'
    ];

    protected $appends = [
        'status_kehadiran_formatted', 'tanggal_booking'
    ];

    public function getTanggalBookingAttribute(){
        return Carbon::parse($this->created_at)->locale('id')->isoFormat('dddd, DD MMMM YYYY, HH:mm');
    }

    public function getStatusKehadiranFormattedAttribute(){
        if($this->status_kehadiran == null){
            return 'Belum dikonfirmasi';
        }elseif($this->status_kehadiran == 0){
            return 'Tidak Hadir';
        }elseif($this->status_kehadiran == 1){
            return 'Hadir';
        }elseif($this->status_kehadiran == 2){
            return 'Izin';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelasActivity()
    {
        return $this->belongsTo(KelasActivity::class, 'id_kelas_activity')->withDefault();
    }
}
