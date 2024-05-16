<?php

namespace App\Http\Controllers;

use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\OrderPaket;
use App\Models\User;
use Carbon\Carbon;

class HelperController extends Controller
{
    public function updateData()
    {
        $jadwal = Carbon::now()->addDays(2);
        $listKelasActivity = KelasActivity::whereDate('jam_mulai', $jadwal)->get();
        foreach ($listKelasActivity as $kelasActivity) {
            // Check jumlah anggota booking kelas
            $jumlahAnggotaMax = $kelasActivity->kelas->max_anggota;

            $bookingKelas = BookingKelas::where('id_kelas_activity', $kelasActivity->id)
                ->where(function ($query) {
                    $query->where('status_kehadiran', 1)
                        ->orWhere('status_kehadiran', null);
                })->count();
            if ($bookingKelas < $jumlahAnggotaMax) {
                $listUser = User::whereHas('roles', function ($query) {
                    $query->where('name', 'Customer');
                })->whereHas('orderPaket', function ($query) use ($kelasActivity) {
                    $query->whereHas('paket', function ($query) use ($kelasActivity) {
                        $query->whereHas('kelasPaket', function ($query) use ($kelasActivity) {
                            $query->where('id_kelas', $kelasActivity->id_kelas);
                        });
                    });
                })->whereHas('bookingKelas', function ($query) use ($kelasActivity) {
                    $query->where('id_kelas_activity', '!=', $kelasActivity->id);
                })->inRandomOrder()->get();

                $sisa = $jumlahAnggotaMax - $bookingKelas;
                foreach ($listUser as $user) {
                    $bookingKelas = BookingKelas::create([
                        'id_user' => $user->id,
                        'id_kelas_activity' => $kelasActivity->id,
                        'is_send_email' => true,
                        'status_kehadiran' => null,
                        'keterangan' => 'Dibooking secara automatis',
                    ]);
                    $sisa++;
                    if ($sisa == $jumlahAnggotaMax) {
                        break;
                    }
                }
            }
        }
        return $listKelasActivity;
    }

    public static function generateUnixKode($harga)
    {
        $pin = mt_rand(100, 999);

        $kode = str_shuffle($pin);

        $jumlahKode = OrderPaket::where('unix_code', $kode)->whereMonth('created_at', now())->count();
        if ($jumlahKode > 2) {
            return static::generateUnixKode($harga);
        }

        return $kode;
    }

    public static function termsConditions()
    {
        return [
            "Keanggotaan" => [
                "Semua data member harus di isi dengan lengkap tanpa terkecuali", "Semua anggota baru harus mendapatkan persetujuan dari management. Management memiliki hak untuk menolak permohonan keanggotaan dengan alasan apapun",
                "Jika disetujui, anggota akan diberikan kartu keanggotaan dan tidak dapat dipindah tangankan.",
                "Jika kartu keanggotaan hilnag, anggota wajib memiliki kartu pengganti dengan biaya sesuai dengan peraturan yang berlaku.",
                "Setiap anggota dilarang masuk ke club tanpa kartu keanggotaan yang aktif.",
                "Jika tidak membawa kartu keanggotaan / belum mendapat, dapat menggunakan KTP / ID Card.",
                "Jika salah satu klub kami berhenti beroperasi, anggota bersedia dialkihkan ke klub kami yang terdekat.",
                "Member dilarang untuk menggunakan fasilitas LIMITLESS FITNESS sebelum startdate dimulai. Durasi membership akan segera diaktifkan apabila member sudah memasuki gate."
            ],
            "Biaya Keanggotaan" => [
                "Semua transaksi pembayaran wajib melalui channel pembayaran resmi LIMITLESS FITNESS Gym.",
                "LIMITLESS FITNESS Gym tidak bertanggung jawab atas transaksi yang dilakukan selain melalui channel pembayaran resmi LIMITLESS FITNESS.",
                "Semua anggota wajib membayar biaya keanggotaan yang sudah ditentukan.",
                "Pembelian Membership / Personal Trainer Packages yang sudah berhasil dibeli, dianggap TELAH MENYETUJUI seluruh perjanjian dan TIDAK DAPAT DIREFUND apapun alasannya.",
                "Bonus hanya dapat di isi sesuai dengan persyaratan dan ketentuan yang berlaku."
            ],
            "Pencabutan dan Pindah Tangahn Keanggotaan" => [
                'LIMITLESS FITNESS Gym dapat melakukan pembatalan kontrak secara sepihak apabila member dinilai merugikan pihak LIMITLESS FITNESS Gym atau member lain.',
                'Membership dan/atau Personal Trainer Packages yang sudah dibeli dapat dipindahtangankan dengan ketentuan yang berlaku dan akan dikenakan biaya sesuai dengan peraturan yang berlaku.'
            ],
            "Keadaan Fisik Anggota" => [
                'Anggota menyatakan dan menjamin kepada LIMITLESS FITNESS Gym bahwa Anggota berada dalam kondisi fisik yang baik dan tidak memiliki alasan medis atau cacat yang dapat menghalangi Anggota untuk menggunakan fasilitas Gym sesuai tujuannya. Anggota mengetahui bahwa LIMITLESS FITNESS Gym belum memberikan nasihat medis apa pun kepada Anggota sebelum Anggota bergabung dengan LIMITLESS FITNESS Gym dan tidak dapat memberikan nasihat tersebut kepada Anggota setelah Anggota bergabung dengan LIMITLESS FITNESS Gym, baik terkait dengan kondisi fisik Anggota dan kemampuan untuk menggunakan fasilitas dan layanan LIMITLESS FITNESS Gym atau lainnya. Anggota mengetahui dan menyetujui bahwa Anggota akan membicarakan masalah kesehatan atau medis apa pun dengan dokter Anggota atau profesional kesehatan lainnya sebelum dan saat menggunakan fasilitas LIMITLESS FITNESS Gym.',
            ],
            "Tuntutan" => [
                'LIMITLESS FITNESS Gym tidak akan bertanggung jawab untuk setiap tuntutan yang timbul yang disebabkan oleh kelalaian anggota.',
            ],
            "Keselamatan" => [
                'Semua anggota yang memiliki umur dibawah 15 tahun atau diatas 60 tahun dan BMI dibawah 18,5 atau diatas 35 wajib memakai Personal Trainer dari LIMITLESS FITNESS Gym',
            ],
            "Jam Operasional" => [
                'Jam operasional club, sesuai dengan peraturan yang berlaku dari management',
            ],
            "Pakaian" => [
                'Selama di area gym, member wajib menggunakan Sepatu, pakaian olahrga dan menjaga kesopanan serta kenyamanan member lain'
            ],
            "Fasilitas Club" => [
                'Member diwajibkan menjaga dan bertanggung jawab atas fasilitas yang digunakan.',
                'LIMITLESS FITNESS Gym sepenuhnya tidak bertanggung jawab atas kerusakan dan kehilangan barang pribadi milik member di dalam seluruh area LIMITLESS FITNESS Gym.',
                'Segala bentuk kerusakan dan kehilangan dari fasilitas yang disediakan dalam LIMITLESS FITNESS Gym yang disebabkan oleh kelalaian member, maka member wajib melakukan penggantian secara full dengan barang yang sama atau membayar dengan harag yang sama.',
                'Tidak diperkenankan kepada setiap member untuk melakukan aktivitas makan di area locker dan area gym lainnya, juga dihimbau kepada seluruh member agar menjaga kebersihan di seluruh area LIMITLESS FITNESS Gym.'
            ],
            "Penyelesaian Perselisihan" => [
                'a.	Apabila di kemudian hari terdapat perselisihan atas perbedaan penafsiran mengenai isi dan atau pelaksanaan peraturan club LIMITLESS FITNESS Gym, maka akan diselesaikan secara musyawarah dan kekeluargaan untuk mufakat',
            ],
            "Cuti Keanggotaan" => [
                'Biaya yang dikenakan untuk per pengajuan cuti dapat berubah sesuai dengan peraturan yang berlaku, 1 pengajuan cuti = 30 hari.',
                'Selama masa cuti anggota tidak dapat menggunakan segala fasilitas gym.',
                'Member dapat memulai Kembali membership sebelum masa cuti habis dengan ketentuan hari cuti yang tersisa dianggap hangus.',
                'Member berhak mendapat cuti dengan ketentuan member yang memiliki masa aktif : 3 bulan = 1x, 6 bulan = 3x, 12 bulan = 4x, 15 bulan = 4x, 24 bulan = 5x',
            ],
            "Check In & Reservasi Kelas" => [
                'Member wajib check-in, class book atau Personal Trainer book melalui admin LIMITLESS FITNESS Gym.',
                "Pemabatalan kelas maksimal H-1 sebelum sesi dimulai"
            ],
            "Lain-Lain" => [
                'Loker pribadi dan handuk tersedia secara gratis jika memiliki membership dengan benefit tersebut',
                'Jika menghilangkan kunci loker atau handuk maka akan dikenakan denda sebesar biaya yang berlaku per item',
                'c.	Dengan menandatangani perjanjian ini, Anda setuju bahwa LIMITLESS FITNESS tidak bertanggung jawab atas barang – barang yang hilang dengan alasan apapun. Anda bertanggung jawab untuk menjaga barang – barang pribadi Anda sendiri.'
            ],
        ];
    }
}
