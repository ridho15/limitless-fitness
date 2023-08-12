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
        'is_active',
        'min_anggota',
        'max_anggota',
        'keterangan',
        'nama_kelas',
        'duration',
        'bawaan',
        'type',
        'honor',
    ];

    protected $appends = [
        'status_aktif',
        'rating',
        'jumlah_review',
        'type_formatted'
    ];

    public function getTypeFormattedAttribute()
    {
        switch ($this->type) {
            case 1:
                return 'Online';
                break;
            case 2:
                return 'Onsite';
                break;
            default:
                return "Not Know";
                break;
        }
    }

    public function getJumlahReviewAttribute()
    {
        $kelasActivity = KelasActivity::where('id_kelas', $this->id)->get();
        $jumlahReview = 0;
        foreach ($kelasActivity as $item) {
            $jumlahReview += $item->kelasRating->count();
        }

        return $jumlahReview;
    }

    public function getRatingAttribute()
    {
        $kelasActivity = KelasActivity::where('id_kelas', $this->id)->get();
        $jumlahReview = 0;
        $jumlahRating = 0;
        foreach ($kelasActivity as $item) {
            foreach ($item->kelasRating as $kelasRating) {
                $jumlahReview++;
                $jumlahRating += $kelasRating->rating;
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

    public function kelasActivity()
    {
        return $this->hasMany(KelasActivity::class, 'id_kelas');
    }

    public function paket(){
        return $this->hasMany(Paket::class, 'id_kelas');
    }
}
