<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingKelas extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'booking_kelas';
    protected $fillable = [
        'id_user',
        'id_kelas_activity',
        'set_jam',
        'is_send_email',
        'status_kehadiran',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelasActivity()
    {
        return $this->belongsTo(KelasActivity::class, 'id_kelas_activity');
    }
}
