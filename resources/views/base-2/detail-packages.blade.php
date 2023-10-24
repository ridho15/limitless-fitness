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
                        @if ($paket->is_many_times == 0)
                            <div class="mb-2">
                                <span class="me-2"><i class="fa-solid fa-1"></i></span>
                                <small>{{ $paket->type_kelas_formatted }} Package only ordered once</small>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-3">What you’re eligible</h4>
                    <ul class="row">
                        <li class="col-md-6">
                            {{ $paket->kelas->nama_kelas }}
                        </li>
                    </ul>
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
                        <button type="button" @if ($isDisable == true||!Auth::user() ) disabled @endif
                            class="btn custom-btn w-100" id="btn_confirm_pembayaran">
                            @if(!Auth::user())
                                Silahkan login terlebih dahulu
                            @elseif ($isDisable == false)
                                Confirm and Pay
                            @else
                                Paket sudah pernah di order
                            @endif
                        </button>
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

    @livewire('packages.confirm-pembayaran', ['id_paket' => $paket->id])
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
