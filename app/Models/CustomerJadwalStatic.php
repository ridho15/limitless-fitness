<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerJadwalStatic extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customer_jadwal_statics';

    protected $fillable = [
        'id_user',
        'hari',
        'jam'
    ];

    protected $appends = [
        'hari_formatted'
    ];

    public function getHariFormattedAttribute()
    {
        switch ($this->hari) {
            case '0':
                return "Senin";
                break;
            case '1':
                return "Selasa";
                break;
            case '2':
                return "Rabu";
                break;
            case '3':
                return "Kamis";
                break;
            case '4':
                return "Jumat";
                break;
            case '5':
                return "Sabtu";
                break;
            case '6':
                return "Minggu";
                break;
            default:
                return "Tidak diketahui";
                break;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
