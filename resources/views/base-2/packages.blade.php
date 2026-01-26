@extends('base-2.layout')

@section('content')
<section class="my-4">
    <div class="container">
        <div class="row my-3">
            <div class="col-md-12">
                <h3 class="fw-bold">Packages</h3>
            </div>
        </div>
        <ul class="nav nav-underline d-flex">
            <li class="nav-item">
                <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab" aria-current="page"
                    href="#all">All</a>
            </li>
            @foreach ($listKelas as $item)
            <li class="nav-item">
                <a class="nav-link px-3" role="tab" data-bs-toggle="tab" href="#kelas_{{ $item->id }}">{{
                    $item->nama_kelas }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="all">
                <div class="row my-5">
                    @if ((is_array($allPaket) || $allPaket instanceof \Countable) && count($allPaket) > 0)
                    @foreach ($allPaket as $item)
                    <div class="col-md-4 mb-3">
                        <div class="custom-card-package p-3 h-100">
                            <div class="d-flex h-100 flex-column align-items-center justify-content-between">
                                <div class="h5 fw-bold w-100 mt-3 text-start">{{ $item->nama }}</div>
                                <div class="mb-4 d-flex justify-content-between w-100">
                                    <small style="text-decoration: line-through red;">
                                        {{ $item->harga_formatted }}
                                    </small><br>
                                    <span class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.')
                                        }}</span>
                                </div>
                                <div class="p-3 mb-4 card-child" style="flex: 1">
                                    <small class="d-block mb-2 fw-light">
                                        <span class="me-2"><i class="fa-solid fa-check" style="color: grey"></i>
                                        </span>
                                        <span>
                                            @foreach ($item->kelas as $kelas)
                                            {{ $kelas->nama_kelas }},
                                            @endforeach
                                        </span>
                                    </small>
                                    <small class="d-block mb-2 fw-light">
                                        <span class="me-2"><i class="fa-solid fa-location-dot" style="color: grey"></i>
                                        </span>
                                        <span>@php
                                            if (is_array($item->location) || $item->location instanceof \Countable) {
                                                if (count($item->location) > 0) {
                                                    $text = "";
                                                    foreach ($item->location as $location) {
                                                        $text .= $location->nama . ", <br>";
                                                    }
                                                    echo $text;
                                                } else {
                                                    echo '-';
                                                }
                                            } else {
                                                echo '-';
                                            }
                                        @endphp</span>
                                    </small>
                                    <small class="d-block mb-2 fw-light">
                                        <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                style="color: grey"></i>
                                        </span>
                                        <span>The package starts from the first time you make a payment and is confirmed
                                            by
                                            the administrator</span>
                                    </small>
                                    @if ($item->is_many_times == 0)
                                    <small class="d-block mb-2 fw-light">
                                        <span class="me-2">
                                            <i class="fa-solid fa-1" style="color: grey"></i>
                                        </span>
                                        <span>Paket hanyak sekali order</span>
                                    </small>
                                    @endif
                                </div>
                                <div class="text-center d-grid gap-2 w-100">
                                    <a href="{{ route('detail-packages', ['id' => $item->id]) }}"
                                        class="btn custom-btn">Buy
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-md-12 text-center py-5">
                        <span class="d-block mb-2">
                            <i class="fa-solid fa-clipboard-list fs-1"></i>
                        </span>
                        <small class="d-block">Belum ada paket</small>
                    </div>
                    @endif
                </div>
            </div>
            @foreach ($listKelas as $item)
            <div class="tab-pane fade" id="kelas_{{ $item->id }}">
                @php
                $filtered = $allPaket->filter(function($paket)use($item){
                foreach ($paket->kelas as $kelas) {
                if($kelas->id == $item->id){
                return true;
                }
                }
                });
                @endphp
                <div class="row my-5">
                    @if ((is_array($filtered) || $filtered instanceof \Countable) && count($filtered) > 0)
                    @foreach ($filtered as $paket)
                    <div class="col-md-4">
                        <div class="custom-card-package p-3 mb-4">
                            <div class="mb-3">
                                <small class="fw-semibold text-uppercase" style="color: #b4b4b4">{{
                                    $paket->kategori_formatted }}</small>
                            </div>
                            <div class="h5 fw-bold mt-3">{{ $paket->nama }}</div>
                            <div class="mb-4 d-flex justify-content-between">
                                <small style="text-decoration: line-through red;">
                                    {{ $paket->harga_formatted }}
                                </small>
                                <span class="fw-bold">Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</span>
                            </div>
                            <div class="p-3 mb-4 card-child" style="">
                                <small class="d-block mb-2 fw-light">
                                    <span class="me-2"><i class="fa-solid fa-check" style="color: grey"></i>
                                    </span>
                                    <span>
                                        @foreach ($paket->kelas as $kelas)
                                        {{ $kelas->nama_kelas }},
                                        @endforeach
                                    </span>
                                </small>
                                <small class="d-block mb-2 fw-light">
                                    <span class="me-2"><i class="fa-solid fa-location-dot" style="color: grey"></i>
                                    </span>
                                    <span>@php
                                        if (is_array($paket->paketLocation) || $paket->paketLocation instanceof \Countable) {
                                            if (count($paket->paketLocation) > 0) {
                                                $text = "";
                                                foreach ($paket->paketLocation as $paketLocation) {
                                                    $text .= $paketLocation->location->nama . ", <br>";
                                                }
                                                echo $text;
                                            } else {
                                                echo '-';
                                            }
                                        } else {
                                            echo '-';
                                        }
                                    @endphp</span>
                                </small>
                                <small class="d-block mb-2 fw-light">
                                    <span class="me-2"><i class="fa-solid fa-clock-rotate-left" style="color: grey"></i>
                                    </span>
                                    <span>Valid For {{ $paket->valid_day }} days from date
                                        of first booking
                                    </span>
                                </small>
                            </div>
                            <div class="text-center d-grid gap-2 w-100">
                                <a href="{{ route('detail-packages', ['id' => $paket->id]) }}"
                                    class="btn custom-btn">Buy Now</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-md-12 text-center py-5">
                        <span class="d-block mb-2">
                            <i class="fa-solid fa-clipboard-list fs-1"></i>
                        </span>
                        <small class="d-block">Belum ada paket</small>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection