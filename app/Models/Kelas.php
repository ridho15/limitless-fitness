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
        'level',
        'duration',
        'bawaan',
        'type',
        'category'
    ];

    protected $appends = [
        'status_aktif', 'rating', 'level_formatted', 'jumlah_review'
    ];

    public function getJumlahReviewAttribute(){
        $kelasActivity = KelasActivity::where('id_kelas', $this->id)->get();
        $jumlahReview = 0;
        foreach($kelasActivity as $item){
            $jumlahReview += $item->kelasRating->count();
        }

        return $jumlahReview;
    }

    public function getLevelFormattedAttribute()
    {
        switch ($this->level) {
            case 0:
                return 'Semua Level';
                break;
            case 1:
                return 'Intermediate';
                break;
            case 2:
                return 'Beginer';
                break;
            case 3:
                return 'Advanced';
                break;
            default:
                return 'Not Know';
                break;
        }
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
}
