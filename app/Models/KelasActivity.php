<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasActivity extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_activities';
    protected $fillable = [
        'id_kelas',
        'nama_latihan',
        'id_ruangan',
        'jam_mulai',
        'id_user',
        'status',
        'level',
        'cancellation_policy',
    ];

    protected $appends = [
        'status_formatted',
        'simple_detail',
        'status_aktivitas_kelas',
        'jam_mulai_formatted',
        'level_formatted'
    ];

    public function getLevelFormattedAttribute()
    {
        if ($this->level == '0') {
            return 'Open to all';
        } elseif ($this->level == '1') {
            return 'Intermediate';
        } elseif ($this->level == '2') {
            return 'Beginer';
        } elseif ($this->level == '3') {
            return 'Advanced';
        }
    }

    public function getJamMulaiFormattedAttribute()
    {
        return Carbon::parse($this->jam_mulai)->locale('id')->isoFormat('DD MMMM YYYY HH:mm');
    }

    public function getStatusAktivitasKelasAttribute()
    {
        $carbon = Carbon::parse($this->jam_mulai)->addHours(1);
        if ($this->status == 0) {
            $this->status = 0;
            $this->save();
            return 'Kelas di batalkan';
        } elseif (now() > $this->jam_mulai && now() <= $carbon) {
            $this->status = 2;
            $this->save();
            return "Kelas Sedang Berlangsung";
        } elseif ($this->jam_mulai < now()) {
            $this->status = 3;
            $this->save();
            return 'Jadwal Kelas Sudah Lewat';
        } elseif (now() < $this->jam_mulai) {
            $bookingKelas = BookingKelas::where('id_kelas_activity', $this->id)->count();
            $this->status = 1;
            $this->save();
            $kelas = Kelas::find($this->id_kelas);
            return $bookingKelas . ' / ' . $kelas->max_anggota . ' Waiting List';
        }
    }

    public function getSimpleDetailAttribute()
    {
        $kelas = Kelas::find($this->id_kelas);
        if($kelas){
            return $kelas->nama_kelas . ' ' . $this->jam_latihan;
        }

        return null;
    }

    public function getStatusFormattedAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'Set';
                break;
            case 0:
                return 'Dibatalkan';
                break;
            case 2:
                return 'Sedang berlangsung';
                break;
            case 3:
                return 'Sudah lewat';
                break;
            default:
                return 'now know';
                break;
        }
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelasRating()
    {
        return $this->hasMany(KelasRating::class, 'id_kelas_activity');
    }

    public function bookingKelas()
    {
        return $this->hasMany(BookingKelas::class, 'id_kelas_activity');
    }

    public function ruangan(){
        return $this->belongsTo(Ruangan::class, 'id_ruangan')->withDefault();
    }
}
