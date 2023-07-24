<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coa extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'coas';
    protected $fillable = [
        'nomor',
        'nama',
        'saldo'
    ];

    protected $appends = [
        'saldo_formatted'
    ];

    public function getSaldoFormattedAttribute()
    {
        return 'Rp.' . number_format($this->saldo, 0, ',', '.');
    }

    public function jurnal()
    {
        return $this->hasMany(Jurnal::class, 'id_coa');
    }
}
