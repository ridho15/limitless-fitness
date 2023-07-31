<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'booking_orders';
    protected $fillable = [
        'id_user',
        'id_paket',
        'harga',
        'status',
        'tanda_tangan',
        'is_active',
        'expired_date',
        'tanggal_pembayaran',
        'bukti_pembayaran',
    ];

    protected $appends = [
        'harga_formatted', 'status_formatted', 'expired_date_formatted', 'tanggal_pembayaran_formatted'
    ];

    public function getTanggalPembayaranFormattedAttribute()
    {
        return Carbon::parse($this->tanggal_pembayaran)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm');
    }

    public function getExpiredDateFormattedAttribute()
    {
        return Carbon::parse($this->expired_date)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm');
    }

    public function getStatusFormattedAttribute()
    {
        switch ($this->status) {
            case 1:
                return 'Lunas';
                break;
            case 0:
                return 'Batal';
                break;
            case 2:
                return 'Belum Lunas';
                break;
            default:
                return 'Not Know';
                break;
        }
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp.' . number_format($this->harga, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
