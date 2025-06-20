<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas';
    protected $fillable = [
        'is_active',
        'min_anggota',
        'max_anggota',
        'keterangan',
        'nama_kelas',
        'honor',
        'fee',
        'id_location',
        'waktu_buka',
        'waktu_tutup',
    ];

    protected $appends = [
        'status_aktif',
    ];

    public function getJumlahReviewAttribute()
    {
        $listKelasActivity = KelasActivity::withCount('kelasRating')->where('id_kelas', $this->id)->get()->toArray();
        $array_column = array_column($listKelasActivity, 'kelas_rating_count');
        return array_sum($array_column);
    }

    public function getRatingAttribute()
    {
        $jumlahReview = $this->getJumlahReviewAttribute();
        $listKelasActivity = KelasActivity::where('id_kelas', $this->id)->with(['kelasRating' => function ($query) {
            $query->select(['id', 'rating']);
        }])->has('kelasRating')->get()->toArray();

        $jumlahRating = 0;
        foreach ($listKelasActivity as $item) {
            foreach ($item['kelasRating'] as $kelasRating) {
                $jumlahRating += $kelasRating['rating'];
            }
        }
        return $jumlahReview == 0 ? 0 : $jumlahRating / $jumlahReview;
    }

    public function getStatusAktifAttribute()
    {
        if ($this->is_active == 1) {
            return 'Aktif';
        } else {
            return 'Tidak Aktif';
        }
    }

    public function kelasImage()
    {
        return $this->hasMany(KelasImage::class, 'id_kelas');
    }

    public function bookingKelas()
    {
        return $this->hasMany(BookingKelas::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location');
    }

    public function instruktur()
    {
        return $this->belongsToMany(User::class, 'kelas_instrukturs', 'id_kelas', 'id_user');
    }

    public function paket()
    {
        return $this->belongsToMany(Paket::class, 'kelas_pakets', 'id_kelas', 'id_paket');
    }
}
