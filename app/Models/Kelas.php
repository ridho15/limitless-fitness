<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas';
    protected $fillable = [
        'id_user',
        'harga_real',
        'is_active',
        'jumlah_pertemuan',
        'min_customer',
        'max_customer',
        'keterangan',
        'diskon',
        'nama_kelas',
        'expired',
        'foto'
    ];

    protected $appends = [
        'harga', 'expired_formatted', 'status_aktif'
    ];

    public function getStatusAktifAttribute()
    {
        if ($this->is_active == 1) {
            return 'Aktif';
        } else {
            return 'Tidak Aktif';
        }
    }

    public function getExpiredFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->locale('id')->addDays($this->expired)->isoFormat('dddd, DD MMMM YYYY');
    }

    public function getHargaAttribute()
    {
        return $this->harga_real - ($this->harga_real * $this->diskon /  100);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
