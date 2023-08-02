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
        'max_anggota',
        'id_location_studio',
        'jam_latihan',
        'id_user',
        'status',
        'cancellation_policy',
    ];

    protected $appends = [
        'status_formatted', 'simple_detail', 'status_aktivitas_kelas', 'jam_latihan_formatted'
    ];

    public function getJamLatihanFormattedAttribute(){
        return Carbon::parse($this->jam_latihan)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm');
    }

    public function getStatusAktivitasKelasAttribute()
    {
        $kelas = Kelas::find($this->id_kelas);
        $carbon = Carbon::parse($this->jam_latihan)->addMinutes($kelas->duration);
        if ($this->status == 0) {
            $this->status = 0;
            $this->save();
            return 'Kelas di batalkan';
        } elseif (now() > $this->jam_latihan && $carbon > now()) {
            $this->status = 2;
            $this->save();
            return "Kelas Sedang Berlangsung";
        } elseif ($carbon < now()) {
            $this->status = 3;
            $this->save();
            return 'Jadwal Kelas Sudah Lewat';
        } elseif (now() < $this->jam_latihan) {
            $bookingKelas = BookingKelas::where('id_kelas_activity', $this->id)->count();
            $this->status = 1;
            $this->save();
            return $bookingKelas . ' Waiting List';
        }
    }

    public function getSimpleDetailAttribute()
    {
        $kelas = Kelas::find($this->id_kelas);
        return $kelas->nama_kelas . ' ' . $this->jam_latihan;
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
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function locationStudio()
    {
        return $this->belongsTo(LocationStudio::class, 'id_location_studio');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelasRating(){
        return $this->hasMany(KelasRating::class, 'id_kelas_activity');
    }
}
