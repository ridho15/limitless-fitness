<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'status',
        'alamat',
        'no_hp',
        'foto',
        'tanggal_lahir',
        'jenis_kelamin',
        'negara',
        'ip_address',
        'id_location',
        'rating',
        'ktp',
        'selfie',
        'nik'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'status_formatted',
        'user_join',
        'jenis_kelamin_formatted',
        'tanggal_lahir_formatted'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessFilament(): bool
    {
        return $this->hasRole(['Super Admin', 'Admin', 'Instruktur', 'Owner Cabang', 'Finance']);
    }

    public function getJenisKelaminFormattedAttribute()
    {
        if ($this->jenis_kelamin == 1) {
            return 'Pria';
        } elseif ($this->jenis_kelamin == 0) {
            return 'Wanita';
        }
    }

    public function getTanggalLahirFormattedAttribute()
    {
        return Carbon::parse($this->tanggal_lahir)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
    }

    public function getStatusFormattedAttribute()
    {
        if ($this->status == 1) {
            return 'Aktif';
        } else {
            return 'Tidak aktif';
        }
    }

    public function getUserJoinAttribute()
    {
        return Carbon::parse($this->created_at)->locale('id')->isoFormat('dddd, DD MMMM YYYY');
    }

    public function orderPaket()
    {
        return $this->hasMany(OrderPaket::class, 'id_user');
    }

    public function kelasActivity()
    {
        return $this->hasMany(KelasActivity::class, 'id_user');
    }

    public function bookingKelas()
    {
        return $this->hasMany(BookingKelas::class, 'id_user');
    }

    public function customerJadwalStatic()
    {
        return $this->hasMany(CustomerJadwalStatic::class, 'id_user');
    }

    public function voucherCustomer()
    {
        return $this->hasMany(VoucherCustomer::class, 'id_customer');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_location')->withDefault();
    }
}
