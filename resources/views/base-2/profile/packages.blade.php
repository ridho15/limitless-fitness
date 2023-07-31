@extends('base-2.layout')

@section('content')
    <section class="my-4 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md py-4">
                    <h4 class="fw-bold">My Packages</h4>
                    <ul class="nav nav-underline d-none d-sm-flex">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab"
                                href="#active">Active</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#expired">Expired</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#tidak_aktif">Tidak Aktif</a>
                        </li>
                    </ul>
                    <hr>
                    <div class="tab-content">
                        <div class="tab-pane fade show active py-4" id="active">
                            @if (count($listPaketAktif) > 0)
                                @foreach ($listPaketAktif as $item)
                                    <div class="bg-white shadow border p-4 mb-3" style="border-radius: 15px">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fw-bold text-start">{{ $item->paket->nama }}</div>
                                                <small class="d-block text-start">{{ $item->paket->jumlah_pertemuan }}
                                                    Pertemuan</small>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-bold text-center">{{ $item->harga_formatted }}</div>
                                                <small class="d-block text-center">Diskon
                                                    {{ $item->paket->diskon }}%</small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="fw-semibold text-danger">{{ $item->expired_date_formatted }}
                                                </div>
                                                @if ($item->status == 1)
                                                    <span class="badge text-bg-success">Lunas</span>
                                                @elseif($item->status == 2)
                                                    <span class="badge text-bg-info">Belum Lunas</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-4 align-items-center justify-content-between">
                                            <div class="col-md-4 fw-bold text-start">Pembayaran</div>
                                            <div class="col-md-4 text-center">
                                                <small class="d-block text-center">
                                                    @if ($item->tanggal_pembayaran == null)
                                                        Belum melakukan pembayaran
                                                    @else
                                                        {{ $item->tanggal_pembayaran_formatted }}
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <form action="{{ route('upload-bukti-pembayaran', ['id' => $item->id]) }}"
                                                    wire: method="POST" id="form_bukti_pembayaran"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @if ($item->bukti_pembayaran == null)
                                                        <label for="bukti_pembayaran" class="btn custom-btn-outline">
                                                            <small>Upload Bukti Pembayaran</small>
                                                        </label>
                                                    @else
                                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}"
                                                            target="_blank" class="btn custom-btn-outline">
                                                            Lihat Bukti Pembayaran
                                                        </a>
                                                    @endif
                                                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran"
                                                        class="form-control" accept="image/jpg, image/jpeg, image/png, .pdf"
                                                        onchange="loadFile(event)" hidden>

                                                    <div class="d-none text-center" id="sub_form">
                                                        <img src="" id="output"
                                                            class="img-fluid mt-3 rounded shadow-sm mb-2" alt="">
                                                        <button type="submit"
                                                            class="btn custom-btn-outline">Submit</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any active packages.
                                    </small>
                                    <a href="{{ route('packages') }}" class="btn custom-btn-outline">Buy Packet</a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade py-4" id="expired">
                            @if (count($listPaketExpired) > 0)
                                @foreach ($listPaketExpired as $item)
                                    <div class="bg-white shadow border p-4 mb-3" style="border-radius: 15px">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fw-bold text-start">{{ $item->paket->nama }}</div>
                                                <small class="d-block text-start">{{ $item->paket->jumlah_pertemuan }}
                                                    Pertemuan</small>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-bold text-center">{{ $item->harga_formatted }}</div>
                                                <small class="d-block text-center">Diskon {{ $item->diskon }}%</small>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fw-semibold text-end text-danger">{{ $item->expired_date }}
                                                </div>
                                                <small class="d-block text-end">{{ $item->status_formatted }}</small>
                                            </div>

                                        </div>
                                        <div class="row mt-4 align-items-center justify-content-between">
                                            <div class="col-md-4 fw-bold text-start">Pembayaran</div>
                                            <div class="col-md-4 text-center">
                                                <small class="d-block text-center">Belum melakukan pembayaran</small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button class="btn custom-btn-outline">
                                                    <small>Upload Bukti Pembayaran</small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any active packages.
                                    </small>
                                    <a href="{{ route('packages') }}" class="btn custom-btn-outline">Buy Packet</a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade py-4" id="tidak_aktif">
                            @if (count($listPaketTidakAktif) > 0)
                                @foreach ($listPaketTidakAktif as $item)
                                    <div class="bg-white shadow border p-4 mb-3" style="border-radius: 15px">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fw-bold text-start">{{ $item->paket->nama }}</div>
                                                <small class="d-block text-start">{{ $item->paket->jumlah_pertemuan }}
                                                    Pertemuan</small>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-bold text-center">{{ $item->harga_formatted }}</div>
                                                <small class="d-block text-center">Diskon {{ $item->diskon }}%</small>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fw-semibold text-end text-danger">{{ $item->expired_date }}
                                                </div>
                                                <small class="d-block text-end">{{ $item->status_formatted }}</small>
                                            </div>

                                        </div>
                                        <div class="row mt-4 align-items-center justify-content-between">
                                            <div class="col-md-4 fw-bold text-start">Pembayaran</div>
                                            <div class="col-md-4 text-center">
                                                <small class="d-block text-center">
                                                    @if ($item->tanggal_pembayaran == null)
                                                        Belum melakukan pembayaran
                                                    @else
                                                        {{ $item->tanggal_pembayaran_formatted }}
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-end d-none">
                                                <button class="btn custom-btn-outline" disabled>
                                                    <small>Upload Bukti Pembayaran</small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="d-flex align-items-center flex-column justify-content-center"
                                    style="height: 50vh">
                                    <span class="fs-1 text-center">
                                        <i class="fa-solid fa-file-pen"></i>
                                    </span>
                                    <small class="d-block mb-3">
                                        You don't have any active packages.
                                    </small>
                                    <a href="{{ route('packages') }}" class="btn custom-btn-outline">Buy Packet</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        var loadFile = function(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
            $('#sub_form').removeClass('d-none')
        };
    </script>
@endsection
