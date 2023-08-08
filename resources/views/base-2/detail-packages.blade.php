@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex mb-5">
                        <small class="fw-light mx-1">Package</small>
                        <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                        <small class="fw-bold mx-1">Confirm and Pay</small>
                    </div>
                    <h3 class="fw-bold mb-4">Your Package</h3>
                    <div class="mb-2 fw-light mb-4">
                        <small class="d-block mb-1">{{ $paket->nama }}</small>
                        <small class="d-block mb-1">Expires in {{ $paket->valid_day }} days</small>
                        <small class="d-block mb-1">Please approach our admin to purchase this package.</small>
                    </div>
                    <div class="border bg-white p-3 my-4" style="border-radius: 15px">
                        <div class="mb-2">
                            <span class="me-2"><i class="fa-regular fa-sun"></i></span>
                            <small>{{ $paket->jumlah_pertemuan }} Credits</small>
                        </div>
                        <div class="mb-2">
                            <span class="me-2"><i class="fa-solid fa-house-user"></i></span>
                            <small>{{ $paket->location_formatted }}</small>
                        </div>
                        <div class="mb-2">
                            <span class="me-2"><i class="fa-solid fa-calendar-days"></i></span>
                            <small>Valid for {{ $paket->valid_day }} Days from date of first booking</small>
                        </div>
                        <div class="mb-2">
                            <span class="me-2"><i class="fa-regular fa-circle-check"></i></span>
                            <small>{{ $paket->type_kelas_formatted }} classes only</small>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3">What you’re eligible</h4>
                    <ul class="row">
                        @if (count($paket->kelasPaket) > 0)
                            @foreach ($paket->kelasPaket as $item)
                                <li class="col-md-6"><small class="fw-light">{{ $item->kelas->nama_kelas }}</small></li>
                            @endforeach
                        @else
                            <div class="col-md-12 fw-light text-center">
                                <small>Tidak adak kelas</small>
                            </div>
                        @endif
                    </ul>
                    {{-- <hr class="my-4">
                    <h3 class="fw-bold mb-4">Promote Code</h3>
                    <div class="input-group input-group">
                        <span class="input-group-text bg-white" id="inputGroup-sizing-lg"><i
                                class="fa-solid fa-tag"></i></span>
                        <input type="text" class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-lg" placeholder="Promote Code"
                            style="padding-top: 15px; padding-bottom: 15px">
                        <span class="input-group-text text-sm bg-white" id="basic-addon2">
                            <small style="font-size: 13px">Apply</small>
                        </span>
                    </div> --}}
                    <hr class="my-5">
                    <small class="d-block fw-light mb-2">
                        By purchasing you agree to the <a href="{{ route('term-condition') }}" target="_blank"
                            class="text-decoration-none text-dark">
                            <strong>
                                Terms & Conditions
                            </strong>
                        </a>
                    </small>
                    <div class="text-center">
                        <button type="button" class="btn custom-btn w-100" id="btn_confirm_pembayaran">Confirm and
                            Pay</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="px-4 py-5 bg-white shadow sticky-top"
                        style="border-radius: 15px; top: 80px; z-index: 2 !important;">
                        <small
                            class="d-block mb-2 fw-semibold text-uppercase text-secondary">{{ $paket->kategori_formatted }}</small>
                        <h5 class="fw-semibold mb-3">{{ $paket->nama }}</h5>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="fw-light">{{ $paket->location_formatted }} access</small>
                            <small class="fw-light">{{ $paket->jumlah_pertemuan }} credits</small>
                        </div>
                        <hr class="my-4" style="opacity: 0.1">
                        <div class="fw-semibold mb-3">Price detail</div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="fw-light">Standard</small>
                            <small class="fw-light">Rp.{{ number_format($paket->harga, 0, ',', '.') }}</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="fw-light">Diskon ({{ $paket->diskon }}%)</small>
                            <small
                                class="fw-light">Rp.{{ number_format(($paket->harga * $paket->diskon) / 100, 0, ',', '.') }}</small>
                        </div>
                        <hr class="my-4" style="opacity: 0.1">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="fw-light">Subtotal</small>
                            <small class="fw-light">Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</small>
                        </div>
                        <hr class="my-4" style="opacity: 0.1">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="fw-bold">Grand Total</small>
                            <small class="fw-bold">Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="modalConfirmPembayaran" tabindex="-1" aria-labelledby="modalConfirmPembayaran"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalConfirmPembayaran">Confirm Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('konfirm-pembayaran-paket', ['paket' => $paket->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            Kamu akan melakukan pembayaran pada:</div>
                        paket : <strong>{{ $paket->nama }}</strong> <br>
                        Harga : <strong>Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</strong>

                        <div class="my-3">
                            <span class="d-block mb-2">Silahkan Kirim Pembayaran Ke Rekening : </span>
                            <div>Bank : <strong>Bank Central Asia</strong></div>
                            <div>
                                Nomor Rekening : <strong>6600-4829-11</strong>
                            </div>
                            <div>
                                Atas Nama : <strong>Gerald Reyner Liando</strong>
                            </div>
                        </div>

                        <div class="my-3">
                            Setelah melakukan pembayaran, diharapkan untuk melakukan confirmasi dan mengirimkan bukti
                            pembayaran pada admin.
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Apakah kamu menerima sayarat dan ketentuan yang berlaku
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {

        });

        $('#btn_confirm_pembayaran').on('click', function() {
            $('#modalConfirmPembayaran').modal('show')
        })
    </script>
@endsection
