@extends('base-2.layout')
@section('css')
<link rel="stylesheet" href="{{ asset('css/voucher.css') }}">
@endsection
@section('content')
<section class="voucher-page my-5 py-5">
    <div class="container">
        <div class="row voucher-row">
            <div class="col-md-3">
                @livewire('profile.left-bar', ['active' => $active])
            </div>
            <div class="col-md-9 ps-5">
                <h4 class="fw-bold">My Voucher</h4>
                <ul class="nav nav-underline">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                            href="#semua">Semua</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                            href="#belum_digunakan">Belum Digunakan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                            href="#sudah_digunakan">Sudah Digunakan</a>
                    </li>
                </ul>
                <hr>
                @if (session()->has('info'))
                <div class="alert alert-info" role="alert">
                    {{ session('info') }}
                </div>
                @endif
                <div class="tab-content">
                    <div class="tab-pane fade py-4 show active" id="semua">
                        <h4 class="fw-bold">Semua</h4>
                        <div class="row voucher-row">
                            @foreach ($listVoucherCustomer as $index => $item)
                            <article class="card fl-left" style="margin-left: 0% !important; margin: 2px !important">
                                <section class="date">
                                    <small>Expired</small>
                                    <time datetime="23th feb">
                                        <span>{{ Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('DD')
                                            }}</span>
                                        <span>{{ Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('MMM')
                                            }}</span>
                                        <span class="mt-1">{{
                                            Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('YYYY')
                                            }}</span>
                                    </time>
                                </section>
                                <section class="card-cont">
                                    <small></small>
                                    <h3>{{ $item->voucher->nama }}</h3>
                                    <div class="even-date">
                                        <span class="me-2">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <time class="ms-2">
                                            <span>{{
                                                Carbon\Carbon::parse($item->tanggal_digunakan)->locale('id')->isoFormat('dddd,
                                                DD MMMM YYYY') }}</span>
                                            <span>{{ date('H:i', strtotime($item->tanggal_digunakan)) }}</span>
                                        </time>
                                    </div>
                                    <div class="even-info">
                                        <span class="me-2">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <p class="ms-2">
                                            {{ $item->orderPaket->location->nama }} - {{
                                            $item->orderPaket->location->alamat }}
                                        </p>
                                    </div>
                                    <a href="#" style="background-color: {{ $item->status_digunakan == 1 ? " #037FDD"
                                        : "#F8504C" }} !important">{{ $item->status_digunakan_formatted }}</a>
                                </section>
                            </article>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade py-4" id="belum_digunakan">
                        <h4 class="fw-bold">Belum Digunakan</h4>
                        <div class="row voucher-row">
                            @foreach ($listVoucherCustomer->where('status_digunakan', 0) as $index => $item)
                            <article class="card fl-left" style="margin-left: 0% !important; margin: 2px !important">
                                <section class="date">
                                    <small>Expired</small>
                                    <time datetime="23th feb">
                                        <span>{{ Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('DD')
                                            }}</span>
                                        <span>{{ Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('MMM')
                                            }}</span>
                                        <span class="mt-1">{{
                                            Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('YYYY')
                                            }}</span>
                                    </time>
                                </section>
                                <section class="card-cont">
                                    <small></small>
                                    <h3>{{ $item->voucher->nama }}</h3>
                                    <div class="even-date">
                                        <span class="me-2">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <time class="ms-2">
                                            <span>{{
                                                Carbon\Carbon::parse($item->tanggal_digunakan)->locale('id')->isoFormat('dddd,
                                                DD MMMM YYYY') }}</span>
                                            <span>{{ date('H:i', strtotime($item->tanggal_digunakan)) }}</span>
                                        </time>
                                    </div>
                                    <div class="even-info">
                                        <span class="me-2">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <p class="ms-2">
                                            {{ $item->orderPaket->location->nama }} - {{
                                            $item->orderPaket->location->alamat }}
                                        </p>
                                    </div>
                                    <a href="#" style="background-color: {{ $item->status_digunakan == 1 ? " #037FDD"
                                        : "#F8504C" }} !important">{{ $item->status_digunakan_formatted }}</a>
                                </section>
                            </article>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade py-4" id="sudah_digunakan">
                        <h4 class="fw-bold">Sudah Digunakan</h4>
                        <div class="row voucher-row">
                            @foreach ($listVoucherCustomer->where('status_digunakan', 1) as $index => $item)
                            <article class="card fl-left" style="margin-left: 0% !important; margin: 2px !important">
                                <section class="date">
                                    <small>Expired</small>
                                    <time datetime="23th feb">
                                        <span>{{ Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('DD')
                                            }}</span>
                                        <span>{{ Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('MMM')
                                            }}</span>
                                        <span class="mt-1">{{
                                            Carbon\Carbon::parse($item->expired)->locale('id')->isoFormat('YYYY')
                                            }}</span>
                                    </time>
                                </section>
                                <section class="card-cont">
                                    <small></small>
                                    <h3>{{ $item->voucher->nama }}</h3>
                                    <div class="even-date">
                                        <span class="me-2">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <time class="ms-2">
                                            <span>{{
                                                Carbon\Carbon::parse($item->tanggal_digunakan)->locale('id')->isoFormat('dddd,
                                                DD MMMM YYYY') }}</span>
                                            <span>{{ date('H:i', strtotime($item->tanggal_digunakan)) }}</span>
                                        </time>
                                    </div>
                                    <div class="even-info">
                                        <span class="me-2">
                                            <i class="fa fa-map-marker"></i>
                                        </span>
                                        <p class="ms-2">
                                            {{ $item->orderPaket->location->nama }} - {{
                                            $item->orderPaket->location->alamat }}
                                        </p>
                                    </div>
                                    <a href="#" style="background-color: {{ $item->status_digunakan == 1 ? " #037FDD"
                                        : "#F8504C" }} !important">{{ $item->status_digunakan_formatted }}</a>
                                </section>
                            </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection