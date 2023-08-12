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
        'id_kelas', // null
        'nama_latihan', // null
        'id_location_studio', // null
        'jam_mulai',
        'id_user', // null
        'status',
        'level', //level
        'tanggal',
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
        $combineJadwal = date('Y-m-d', strtotime($this->tanggal)) . ' ' . date('H:i:s', strtotime($this->jam_mulai));
        $carbon = Carbon::parse($combineJadwal)->addHours(1)->isoFormat('YYYY-MM-DD HH:mm:ss');
        if ($this->status == 0) {
            $this->status = 0;
            $this->save();
            return 'Kelas di batalkan';
        } elseif (now() > $combineJadwal && now() <= $carbon) {
            $this->status = 2;
            $this->save();
            return "Kelas Sedang Berlangsung";
        } elseif ($combineJadwal < now()) {
            $this->status = 3;
            $this->save();
            return 'Jadwal Kelas Sudah Lewat';
        } elseif (now() < $combineJadwal) {
            $bookingKelas = BookingKelas::where('id_kelas_activity', $this->id)->count();
            $kelas = Kelas::find($this->id_kelas);
            if ($kelas) {
                if ($bookingKelas == $kelas->max_anggota) {
                    $this->status = 4;
                    $this->save();
                    return $bookingKelas . ' / ' . $kelas->max_anggota . ' Class Full';
                } elseif ($bookingKelas < $kelas->max_anggota) {
                    $this->status = 1;
                    $this->save();
                    return $bookingKelas . ' / ' . $kelas->max_anggota . ' Waiting List';
                }
            } else {
                $this->status == 1;
                $this->save();
                return 'Kelas belum ditentukan';
            }
        }
    }

    public function getSimpleDetailAttribute()
    {
        $kelas = Kelas::find($this->id_kelas);
        if ($kelas) {
            return $kelas->nama_kelas . ' ' . $this->jam_mulai;
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
            case 4:
                return 'Kelas Penuh';
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
        return $this->belongsTo(User::class, 'id_user')->withDefault();
    }

    public function kelasRating()
    {
        return $this->hasMany(KelasRating::class, 'id_kelas_activity');
    }

    public function bookingKelas()
    {
        return $this->hasMany(BookingKelas::class, 'id_kelas_activity');
    }

    public function locationStudio()
    {
        return $this->belongsTo(LocationStudio::class, 'id_location_studio');
    }
}
