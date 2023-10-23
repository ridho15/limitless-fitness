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
                        <a class="nav-link px-3" role="tab" data-bs-toggle="tab"
                            href="#kelas_{{ $item->id }}">{{ $item->nama_kelas }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all">
                    <div class="row my-5">
                        @if (count($allPaket) > 0)
                            @foreach ($allPaket as $item)
                                <div class="col-md-3">
                                    <div class="custom-card-package p-3 mb-4">
                                        <div class="mb-3">
                                            <small class="fw-semibold text-uppercase"
                                                style="color: #b4b4b4">{{ $item->kategori_formatted }}</small>
                                        </div>
                                        <div class="h5 fw-bold mt-3">{{ $item->nama }}</div>
                                        <div class="mb-4 d-flex justify-content-between">
                                            <small style="text-decoration: line-through red;">
                                                {{ $item->harga_formatted }}
                                            </small><br>
                                            <span
                                                class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="p-3 mb-4 card-child" style="">
                                            <small class="d-block mb-2 fw-light">
                                                <span class="me-2"><i class="fa-solid fa-check" style="color: grey"></i>
                                                </span>
                                                <span>{{ $item->jumlah_pertemuan }}
                                                    Credits</span>
                                            </small>
                                            <small class="d-block mb-2 fw-light">
                                                <span class="me-2"><i class="fa-solid fa-location-dot"
                                                        style="color: grey"></i>
                                                </span>
                                                <span>{{ $item->location_formatted }}</span>
                                            </small>
                                            <small class="d-block mb-2 fw-light">
                                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                        style="color: grey"></i>
                                                </span>
                                                <span>Valid For {{ $item->valid_day }} days from date
                                                    of first booking
                                                </span>
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
                                        <div class="text-center d-grid gap-2">
                                            <a href="{{ route('detail-packages', ['id' => $item->id]) }}"
                                                class="btn custom-btn">Buy
                                                Now</a>
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
                        <div class="row my-5">
                            @if (count($item->paket) > 0)
                                @foreach ($item->paket as $paket)
                                    <div class="col-md-3">
                                        <div class="custom-card-package p-3 mb-4">
                                            <div class="mb-3">
                                                <small class="fw-semibold text-uppercase"
                                                    style="color: #b4b4b4">{{ $paket->kategori_formatted }}</small>
                                            </div>
                                            <div class="h5 fw-bold mt-3">{{ $paket->nama }}</div>
                                            <div class="mb-4 d-flex justify-content-between">
                                                <small style="text-decoration: line-through red;">
                                                    {{ $paket->harga_formatted }}
                                                </small>
                                                <span
                                                    class="fw-bold">Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="p-3 mb-4 card-child" style="">
                                                <small class="d-block mb-2 fw-light">
                                                    <span class="me-2"><i class="fa-solid fa-check"
                                                            style="color: grey"></i>
                                                    </span>
                                                    <span>{{ $paket->jumlah_pertemuan }}
                                                        Credits</span>
                                                </small>
                                                <small class="d-block mb-2 fw-light">
                                                    <span class="me-2"><i class="fa-solid fa-location-dot"
                                                            style="color: grey"></i>
                                                    </span>
                                                    <span>{{ $paket->location_formatted }}</span>
                                                </small>
                                                <small class="d-block mb-2 fw-light">
                                                    <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                            style="color: grey"></i>
                                                    </span>
                                                    <span>Valid For {{ $paket->valid_day }} days from date
                                                        of first booking
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="text-center d-grid gap-2">
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
            {{-- <div class="row d-sm-none my-3 align-items-center justify-content-center">
                <div class="dropdown col-12">
                    <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item nav-link active custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#all">All</a></li>
                        @foreach ($listKelas as $item)
                            <li><a class="dropdown-item nav-link px-3" role="tab" data-bs-toggle="tab"
                                    href="#kelas_{{ $item->id }}">{{ $item->nama_kelas }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div> --}}

        </div>
    </section>
@endsection
