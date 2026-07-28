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
        'id_kelas',
        'id_kelas_activity',
        'is_send_email',
        'status_kehadiran',
        'keterangan',
        'review',
        'rating',
    ];

    protected $appends = [
        'status_kehadiran_formatted',
        'tanggal_booking',
        'honor'
    ];

    public function getHonorAttribute()
    {
        $kelasActivity = KelasActivity::find($this->id_kelas_activity);
        if ($kelasActivity) {
            return $kelasActivity->kelas->honor;
        } else {
            return 0;
        }
    }

    public function getTanggalBookingAttribute()
    {
        return Carbon::parse($this->created_at)->locale('id')->isoFormat('dddd, DD MMMM YYYY, HH:mm');
    }

    public function getStatusKehadiranFormattedAttribute()
    {
        if ($this->status_kehadiran == null) {
            return 'Belum dikonfirmasi';
        } elseif ($this->status_kehadiran == 0) {
            return 'Tidak Hadir';
        } elseif ($this->status_kehadiran == 1) {
            return 'Akan hadir';
        } elseif ($this->status_kehadiran == 2) {
            return 'Hadir';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user')->withDefault();
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withDefault();
    }

    public function kelasActivity()
    {
        // Prefer direct relation to KelasActivity when id_kelas_activity column is present
        return $this->belongsTo(KelasActivity::class, 'id_kelas_activity')->withDefault();
    }
}
