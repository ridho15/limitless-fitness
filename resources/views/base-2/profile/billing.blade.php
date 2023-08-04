@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md-9 ps-5">
                    <h4 class="fw-bold">Billing</h4>
                    <ul class="nav nav-underline d-none d-sm-flex">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab"
                                href="#payment_due">Payment Due</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#transaction_history">Transaction history</a>
                        </li>
                    </ul>
                    <hr>
                    @if (session()->has('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane fade show active py-4" id="payment_due">
                            @if (count($listPaymentDue))
                                @foreach ($listPaymentDue as $item)
                                    <div class="bg-white shadow border p-4 mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fw-bold text-start">
                                                    <a href="{{ route('detail-packages', ['id' => $item->paket->id]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->paket->nama }}
                                                    </a>
                                                </div>
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
                                                    <span class="badge text-bg-info text-white">Belum Lunas</span>
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
                        <div class="tab-pane fade py-4" id="transaction_history">
                            @if (count($listTransactionHistory))
                                @foreach ($listTransactionHistory as $index => $item)
                                    <div class="bg-white shadow border p-4 mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fw-bold text-start">
                                                    <a href="{{ route('detail-packages', ['id' => $item->paket->id]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->paket->nama }}
                                                    </a>
                                                </div>
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
                                                    <span class="badge text-bg-info text-white">Belum Lunas</span>
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
                                                            <small>
                                                                @if ($item->expired_date <= now())
                                                                    The order has expired
                                                                @else
                                                                    Upload Bukti Pembayaran
                                                                @endif
                                                            </small>
                                                        </label>
                                                    @else
                                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}"
                                                            target="_blank" class="btn custom-btn-outline">
                                                            <small>Lihat Bukti Pembayaran</small>
                                                        </a>
                                                    @endif
                                                    <input type="file" id="bukti_pembayaran_{{ $index }}"
                                                        name="bukti_pembayaran" class="form-control"
                                                        accept="image/jpg, image/jpeg, image/png, .pdf"
                                                        @if ($item->expired_date <= now()) disabled @endif
                                                        onchange="loadFile(event)" hidden>

                                                    <div class="d-none text-center" id="sub_form">
                                                        <img src="" id="output"
                                                            class="img-fluid mt-3 rounded shadow-sm mb-2" alt="">
                                                        <button type="submit"
                                                            class="btn custom-btn-outline"><small>Submit</small></button>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
