<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasPertemuanAbsensi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_pertemuan_absensis';
    protected $fillable = [
        'id_kelas_pertemuan',
        'id_user',
        'waktu_masuk',
        'status',
        'keterangan'
    ];

    protected $appends = [
        'status_formatted', 'waktu_masuk_formatted'
    ];

    public function getWaktuMasukFormattedAttribute(){
        return Carbon::parse($this->waktu_masuk)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm');
    }

    public function getStatusFormattedAttribute()
    {
        if ($this->status == 1) {
            return 'Masuk';
        } else if ($this->status == 0) {
            return 'Tidak Hadir';
        } else if ($this->status == 2) {
            return 'Izin';
        }
    }

    public function kelasPertemuan(){
        return $this->belongsTo(KelasPertamuan::class, 'id_kelas_pertemuan');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
