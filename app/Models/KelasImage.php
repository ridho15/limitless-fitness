<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_images';
    protected $fillable = [
        'id_kelas',
        'image'
    ];

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
