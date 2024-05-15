<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalConfirmPembayaran" tabindex="-1"
        aria-labelledby="modalConfirmPembayaran" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalConfirmPembayaran">Confirm Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" wire:submit.prevent='konfirmasiPembayaran' method="POST">
                    @csrf
                    <div class="modal-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session()->get('success') }}
                            </div>
                        @elseif(session()->has('fail'))
                            <div class="alert alert-danger" role="alert">
                                {{ session()->get('fail') }}
                            </div>
                        @endif
                        <div class="mb-3">
                            Kamu akan melakukan pembayaran pada:</div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Paket
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $paket->nama }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Harga
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Potongan Voucher
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($potongan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Biaya Penanganan
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($unixcode, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Total
                            </div>
                            <div class="col-md col h5">
                                :
                                <strong
                                    class="text-danger">Rp.{{ number_format($paket->harga_diskon - $potongan + $unixcode, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="my-3">
                            <span class="d-block mb-2">Silahkan Kirim Pembayaran Ke Rekening : </span>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    Bank
                                </div>
                                <div class="col-md col">
                                    : <strong>{{ $bank ?? 'Bank Central Asia' }}</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    Nomor Rekening
                                </div>
                                <div class="col-md col">
                                    : <strong>{{ $no_rekening ?? '6600-4829-11' }}</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    Atas Nama
                                </div>
                                <div class="col-md col">
                                    : <strong>{{ $atas_nama ?? 'Gerald Reyner Liando' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="my-3">
                            <ol>
                                @foreach ($termsConditions as $index => $item)
                                <li class="text-uppercase">{{ $index }}</li>
                                @endforeach 
                            </ol>
                            <ol>
                                <li>KEANGGOTAAN</li>
                            </ol>
                            <ul class="ms-1">
                                <li>Semua data member harus di isi dengan lengkap tanpa terkecuali</li>
                                <li>Semua anggota baru harus mendapatkan persetujuan dari management. Management
                                    memiliki hak
                                    untuk menolak permohonan keanggotaan dengan alasan apapun</li>
                                <li>Jika disetujui, anggota akan diberikan kartu keanggotaan dan tidak dapat dipindah
                                    tangankan</li>
                                <li>Jika kartu keanggotaan hilnag, anggota wajib memiliki kartu pengganti dengan biaya
                                    sesuai</li>
                                    <li></li>
                            </ul>
                            <div class="fw-bold"></div>
                            <div class="ms-1"></div>

                            c. .
                            d.
                            dengan peraturan yang berlaku.
                            e. Setiap anggota dilarang masuk ke club tanpa kartu keanggotaan yang aktif.
                            f. Jika tidak membawa kartu keanggotaan / belum mendapat, dapat menggunakan KTP / ID Card.
                            g. Jika salah satu klub kami berhenti beroperasi, anggota bersedia dialkihkan ke klub kami
                            yang terdekat.
                            h. Member dilarang untuk menggunakan fasilitas LIMITLESS FITNESS sebelum startdate dimulai.
                            Durasi membership akan segera diaktifkan apabila member sudah memasuki gate.

                            2. BIAYA KEANGGOTAAN
                            a. Semua transaksi pembayaran wajib melalui channel pembayaran resmi LIMITLESS FITNESS Gym.
                            b. LIMITLESS FITNESS Gym tidak bertanggung jawab atas transaksi yang dilakukan selain
                            melalui channel pembayaran resmi LIMITLESS FITNESS.
                            c. Semua anggota wajib membayar biaya keanggotaan yang sudah ditentukan.
                            d. Pembelian Membership / Personal Trainer Packages yang sudah berhasil dibeli, dianggap
                            TELAH MENYETUJUI seluruh perjanjian dan TIDAK DAPAT DIREFUND apapun alasannya.
                            e. Bonus hanya dapat di isi sesuai dengan persyaratan dan ketentuan yang berlaku.

                            3. PENCABUTAN DAN PINDAH TANGAN KEANGGOTAAN
                            a. LIMITLESS FITNESS Gym dapat melakukan pembatalan kontrak secara sepihak apabila member
                            dinilai merugikan pihak LIMITLESS FITNESS Gym atau member lain.
                            b. Membership dan/atau Personal Trainer Packages yang sudah dibeli dapat dipindahtangankan
                            dengan ketentuan yang berlaku dan akan dikenakan biaya sesuai dengan peraturan yang berlaku.

                            4. KEADAAN FISIK ANGGOTA
                            a. Anggota menyatakan dan menjamin kepada LIMITLESS FITNESS Gym bahwa Anggota berada dalam
                            kondisi fisik yang baik dan tidak memiliki alasan medis atau cacat yang dapat menghalangi
                            Anggota untuk

                            menggunakan fasilitas Gym sesuai tujuannya. Anggota mengetahui bahwa LIMITLESS FITNESS Gym
                            belum memberikan nasihat medis apa pun kepada Anggota sebelum Anggota bergabung dengan
                            LIMITLESS FITNESS Gym dan tidak dapat memberikan nasihat tersebut kepada Anggota setelah
                            Anggota bergabung dengan LIMITLESS FITNESS Gym, baik terkait dengan kondisi fisik Anggota
                            dan kemampuan untuk menggunakan fasilitas dan layanan LIMITLESS FITNESS Gym atau lainnya.
                            Anggota mengetahui dan menyetujui bahwa Anggota akan membicarakan masalah kesehatan atau
                            medis apa pun dengan dokter Anggota atau profesional kesehatan lainnya sebelum dan saat
                            menggunakan fasilitas LIMITLESS FITNESS Gym.

                            TUNTUTAN
                            a. LIMITLESS FITNESS Gym tidak akan bertanggung jawab untuk setiap tuntutan yang timbul yang
                            disebabkan oleh kelalaian anggota.


                            6. KESELAMATAN
                            a. Semua anggota yang memiliki umur dibawah 15 tahun atau diatas 60 tahun dan BMI dibawah
                            18,5 atau diatas 35 wajib memakai Personal Trainer dari LIMITLESS FITNESS Gym

                            7. JAM OPERASIONAL
                            a. Jam operasional club, sesuai dengan peraturan yang berlaku dari management.

                            8. PAKAIAN
                            a. Selama di area gym, member wajib menggunakan Sepatu, pakaian olahrga dan menjaga
                            kesopanan serta kenyamanan member lain.

                            9. FASILITAS CLUB
                            a. Member diwajibkan menjaga dan bertanggung jawab atas fasilitas yang digunakan.
                            b. LIMITLESS FITNESS Gym sepenuhnya tidak bertanggung jawab atas kerusakan dan kehilangan
                            barang pribadi milik member di dalam seluruh area LIMITLESS FITNESS Gym.
                            c. Segala bentuk kerusakan dan kehilangan dari fasilitas yang disediakan dalam LIMITLESS
                            FITNESS Gym yang disebabkan oleh kelalaian member, maka member wajib melakukan penggantian
                            secara full dengan barang yang sama atau membayar dengan harag yang sama.
                            d. Tidak diperkenankan kepada setiap member untuk melakukan aktivitas makan di area locker
                            dan area gym lainnya, juga dihimbau kepada seluruh member agar menjaga kebersihan di seluruh
                            area LIMITLESS FITNESS Gym.

                            10. PENYELASAIAN PERSELISIHAN
                            a. Apabila di kemudian hari terdapat perselisihan atas perbedaan penafsiran mengenai isi dan
                            atau pelaksanaan peraturan club LIMITLESS FITNESS Gym, maka akan diselesaikan secara
                            musyawarah dan kekeluargaan untuk mufakat.

                            11. CUTI KEANGGOTAAN
                            a. Biaya yang dikenakan untuk per pengajuan cuti dapat berubah sesuai dengan peraturan yang
                            berlaku, 1 pengajuan cuti = 30 hari.
                            b. Selama masa cuti anggota tidak dapat menggunakan segala fasilitas gym.
                            c. Member dapat memulai Kembali membership sebelum masa cuti habis dengan ketentuan hari
                            cuti yang tersisa dianggap hangus.
                            d. Member berhak mendapat cuti dengan ketentuan member yang memiliki masa aktif :
                            - 3 bulan = 1x
                            - 6 bulan = 3x
                            - 12 bulan = 4x
                            - 15 bulan = 4x
                            - 24 bulan = 5x

                            12. CHECK IN & RESERVASI KELAS
                            a. Member wajib check-in, class book atau Personal Trainer book melalui admin LIMITLESS
                            FITNESS Gym.
                            b. Pemabatalan kelas maksimal H-1 sebelum sesi dimulai.

                            13. LAIN – LAIN
                            a. Loker pribadi dan handuk tersedia secara gratis jika memiliki membership dengan benefit
                            tersebut
                            b. Jika menghilangkan kunci loker atau handuk maka akan dikenakan denda sebesar biaya yang
                            berlaku per item.
                            c. Dengan menandatangani perjanjian ini, Anda setuju bahwa LIMITLESS FITNESS tidak
                            bertanggung jawab atas barang – barang yang hilang dengan alasan apapun. Anda bertanggung
                            jawab untuk menjaga barang – barang pribadi Anda sendiri.


                            Setelah melakukan pembayaran, diharapkan untuk melakukan confirmasi dan mengirimkan bukti
                            pembayaran pada admin.
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                                required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Apakah kamu menerima syarat dan ketentuan yang berlaku
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom-btn-white" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom-btn"
                            @if (!Auth::user()) disabled @endif>Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script></script>
@endpush
