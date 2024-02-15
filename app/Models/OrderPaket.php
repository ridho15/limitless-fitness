<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPaket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'order_pakets';
    protected $fillable = [
        'id_user',
        'id_paket',
        'harga',
        'status',
        'is_active',
        'expired_date',
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'jumlah_pertemuan',
        'sisa',
        'id_location',
        'unix_code'
    ];

    protected $appends = [
        'harga_formatted',
        'status_formatted',
        'expired_date_formatted',
        'tanggal_pembayaran_formatted',
        'sisa_session'
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
            case 3:
                return 'Aktif';
            default:
                return 'Not Know';
                break;
        }
    }

    public function getSisaSessionAttribute()
    {
        $bookingKelas = BookingKelas::whereHas('kelasActivity', function ($query) {
            $query->whereHas('kelas', function ($query) {
                $query->whereHas('paket', function ($query) {
                    $query->whereHas('orderPaket', function ($query) {
                        $query->where('id_user', $this->id_user);
                    });
                });
            });
        })->where('id_user', $this->id_user)
            ->where(function ($query) {
                $query->where('status_kehadiran', '0')
                    ->orWhere('status_kehadiran', '1');
            })->count();

        $sisa = $this->jumlah_pertemuan - $bookingKelas;
        return $sisa;
    }

    public function getHargaFormattedAttribute()
    {
        return 'Rp.' . number_format($this->harga, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user')->withDefault();
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket')->withDefault();
    }

    public function jurnal()
    {
        return $this->hasOne(Jurnal::class, 'id_order_paket');
    }

    public function voucherCustomer()
    {
        return $this->hasOne(VoucherCustomer::class, 'id_order_paket')->withDefault();
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location')->withDefault();
    }
}
