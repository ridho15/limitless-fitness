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
        'duration',
        'bawaan',
        'type',
        'honor',
        'fee',
        'id_location'
    ];

    protected $appends = [
        'status_aktif',
        // 'rating',
        // 'jumlah_review',
        'type_formatted',
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

    public function kelasActivity()
    {
        return $this->hasMany(KelasActivity::class, 'id_kelas');
    }

    public function paket()
    {
        return $this->hasMany(Paket::class, 'id_kelas');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location');
    }
}
