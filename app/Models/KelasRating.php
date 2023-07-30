<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasRating extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_ratings';
    protected $fillable = [
        'id_user',
        'id_kelas',
        'comment',
        'rating'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
