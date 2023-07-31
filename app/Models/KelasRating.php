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
        'id_kelas_activity',
        'comment',
        'rating'
    ];

    protected $appends = [
        'waktu_lalu'
    ];

    public function getWaktuLaluAttribute()
    {
        $diffInDays = $this->created_at->diffInDays();
        $showDiff = $this->created_at->diffForHumans();
        if ($diffInDays > 0) $showDiff .= ', ' . $this->created_at->addDays($diffInDays)->diffInHours() . ' Hours';
        return $showDiff;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelasActivity()
    {
        return $this->belongsTo(KelasActivity::class, 'id_kelas_activity');
    }
}
