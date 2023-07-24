<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasPertamuan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kelas_pertamuans';
    protected $fillable = [
        'id_kelas',
        'datetime_avaiable',
        'status',
        'keterangan'
    ];

    protected $appends = [
        'status_formatted', 'datetime_formatted'
    ];

    public function getDatetimeFormattedAttribute()
    {
        return Carbon::parse($this->datetime_avaiable)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm');
    }

    public function getStatusFormattedAttribute()
    {
        if ($this->status == 1) {
            return 'Avaiable';
        } else {
            return 'Dibatalkan';
        }
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
