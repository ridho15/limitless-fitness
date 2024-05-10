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
                        <small>
                            @php
                            if (count($paket->paketLocation) > 0) {
                            $text = "";
                            foreach ($paket->paketLocation as $item) {
                            $text .= $item->location->nama . ", ";
                            }
                            echo $text;
                            } else {
                            echo "-";
                            }

                            @endphp
                        </small>
                    </div>
                    <div class="mb-2">
                        <span class="me-2"><i class="fa-solid fa-calendar-days"></i></span>
                        <small>The package starts from the first time you make a payment and is confirmed by the administrator</small>
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
                    <button type="button" @if ($isDisable==true || !Auth::user()) disabled @endif
                        class="btn custom-btn w-100" id="btn_confirm_pembayaran">
                        @if (!Auth::user())
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
                @livewire('packages.detail-pembayaran', ['id_paket' => $paket->id])
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