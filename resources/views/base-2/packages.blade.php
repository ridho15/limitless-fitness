@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row my-3">
                <div class="col-md-12">
                    <h3 class="fw-bold">Packages</h3>
                </div>
            </div>
            <ul class="nav nav-underline d-none d-sm-flex">
                <li class="nav-item">
                    <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab" aria-current="page"
                        href="#all">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" role="tab" data-bs-toggle="tab" href="#group_class">Group Class</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" role="tab" data-bs-toggle="tab" href="#private_class">Private Class</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" role="tab" data-bs-toggle="tab" href="#private_duet">Private Duet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" role="tab" data-bs-toggle="tab" href="#private_group">Private Group</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="all">
                    <div class="row my-5">
                        @if (count($listPaket) > 0)
                            @foreach ($listPaket as $item)
                                <div class="col-md-3">
                                    <div class="custom-card-package p-3 mb-4">
                                        <div class="mb-3">
                                            <small class="fw-semibold text-uppercase"
                                                style="color: #b4b4b4">{{ $item->kategori_formatted }}</small>
                                        </div>
                                        <div class="h5 fw-bold mt-3">{{ $item->nama }}</div>
                                        <div class="mb-4">
                                            <small
                                                class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.') }}</small>
                                        </div>
                                        <div class="p-3 mb-4 card-child" style="">
                                            <small class="d-block mb-2 fw-light">
                                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                        style="color: grey"></i>
                                                </span>
                                                <span>{{ $item->jumlah_pertemuan }}
                                                    Credits</span>
                                            </small>
                                            <small class="d-block mb-2 fw-light">
                                                <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
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
                <div class="tab-pane fade" id="group_class">
                    <div class="row my-5">
                        @foreach ($listPaket->where('kategori_kelas', 1) as $item)
                            <div class="col-md-3">
                                <div class="custom-card-package p-3 mb-4">
                                    <div class="mb-3">
                                        <small class="fw-semibold text-uppercase"
                                            style="color: #b4b4b4">{{ $item->kategori_formatted }}</small>
                                    </div>
                                    <div class="h5 fw-bold mt-3">{{ $item->nama }}</div>
                                    <div class="mb-4">
                                        <small
                                            class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="p-3 mb-4 card-child" style="">
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                    style="color: grey"></i>
                                            </span>
                                            <span>{{ $item->jumlah_pertemuan }}
                                                Credits</span>
                                        </small>
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
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
                                    </div>
                                    <div class="text-center d-grid gap-2">
                                        <a href="{{ route('detail-packages', ['id' => $item->id]) }}"
                                            class="btn custom-btn">Buy Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="private_class">
                    <div class="row my-5">
                        @foreach ($listPaket->where('kategori_kelas', 2) as $item)
                            <div class="col-md-3">
                                <div class="custom-card-package p-3 mb-4">
                                    <div class="mb-3">
                                        <small class="fw-semibold text-uppercase"
                                            style="color: #b4b4b4">{{ $item->kategori_formatted }}</small>
                                    </div>
                                    <div class="h5 fw-bold mt-3">{{ $item->nama }}</div>
                                    <div class="mb-4">
                                        <small
                                            class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="p-3 mb-4 card-child" style="">
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                    style="color: grey"></i>
                                            </span>
                                            <span>{{ $item->jumlah_pertemuan }}
                                                Credits</span>
                                        </small>
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
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
                                    </div>
                                    <div class="text-center d-grid gap-2">
                                        <a href="{{ route('detail-packages', ['id' => 1]) }}" class="btn custom-btn">Buy
                                            Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="private_duet">
                    <div class="row my-5">
                        @foreach ($listPaket->where('kategori_kelas', 3) as $item)
                            <div class="col-md-3">
                                <div class="custom-card-package p-3 mb-4">
                                    <div class="mb-3">
                                        <small class="fw-semibold text-uppercase"
                                            style="color: #b4b4b4">{{ $item->kategori_formatted }}</small>
                                    </div>
                                    <div class="h5 fw-bold mt-3">{{ $item->nama }}</div>
                                    <div class="mb-4">
                                        <small
                                            class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="p-3 mb-4 card-child" style="">
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                    style="color: grey"></i>
                                            </span>
                                            <span>{{ $item->jumlah_pertemuan }}
                                                Credits</span>
                                        </small>
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
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
                                    </div>
                                    <div class="text-center d-grid gap-2">
                                        <a href="{{ route('detail-packages', ['id' => 1]) }}" class="btn custom-btn">Buy
                                            Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="private_group">
                    <div class="row my-5">
                        @foreach ($listPaket->where('kategori_kelas', 4) as $item)
                            <div class="col-md-3">
                                <div class="custom-card-package p-3 mb-4">
                                    <div class="mb-3">
                                        <small class="fw-semibold text-uppercase"
                                            style="color: #b4b4b4">{{ $item->kategori_formatted }}</small>
                                    </div>
                                    <div class="h5 fw-bold mt-3">{{ $item->nama }}</div>
                                    <div class="mb-4">
                                        <small
                                            class="fw-bold">Rp.{{ number_format($item->harga_diskon, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="p-3 mb-4 card-child" style="">
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
                                                    style="color: grey"></i>
                                            </span>
                                            <span>{{ $item->jumlah_pertemuan }}
                                                Credits</span>
                                        </small>
                                        <small class="d-block mb-2 fw-light">
                                            <span class="me-2"><i class="fa-solid fa-clock-rotate-left"
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
                                    </div>
                                    <div class="text-center d-grid gap-2">
                                        <a href="{{ route('detail-packages', ['id' => 1]) }}" class="btn custom-btn">Buy
                                            Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row d-sm-none my-3 align-items-center justify-content-center">
                <div class="dropdown col-12">
                    <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        All
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#">All</a></li>
                        <li><a class="dropdown-item" href="#">Group Class</a></li>
                        <li><a class="dropdown-item" href="#">Private Class</a></li>
                        <li><a class="dropdown-item" href="#">Private Duet</a></li>
                        <li><a class="dropdown-item" href="#">Private Group</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </section>
@endsection
