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
                    <ul class="nav nav-underline d-flex">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active custom-nav-link px-3" role="tab" data-bs-toggle="tab"
                                href="#payment_due">Payment Due</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link custom-nav-link px-3" role="tab" data-bs-toggle="tab"
                                href="#generate_invoice">Completed Transactions</a>
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
                                    <div class="bg-white shadow border p-4 mb-3" style="border-radius: 15px">
                                        <div class="row">
                                            <div
                                                class="col-md-4 d-flex d-md-block justify-content-between align-items-center">
                                                <div class="fw-bold text-start"> <a
                                                        href="{{ route('detail-packages', ['id' => $item->paket->id ?? 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->paket->nama ?? 'Paket dihapus' }}
                                                    </a></div>
                                            </div>
                                            <div class="col-md-4 text-center mt-2 mt-md-0">
                                                <div class="fw-bold text-center">{{ $item->harga_formatted }}</div>
                                                <small class="d-block text-center">Harga sudah di potonga diskon
                                                    <br></small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="text-center">
                                                    <small>Code : {{ $item->unix_code ?? '-' }}</small>
                                                    <a href="{{ route('profile.invoice', ['id' => $item->id]) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Invoice" type="button">
                                                        <i class="fa-solid fa-file-invoice"></i>
                                                    </a>
                                                </div>
                                                @if ($item->status == 1)
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="fs-2 text-success mx-1"><i
                                                                class="fa-regular fa-circle-check"></i></span>
                                                        <span class="badge text-bg-success mx-1">Lunas</span>
                                                    </div>
                                                @elseif($item->status == 2)
                                                    <div class="text-center">
                                                        <span class="badge text-bg-info text-white">Belum Lunas</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-4 align-items-center justify-content-between">
                                            <div class="col-md-4"><small class="">
                                                    Expired Date Pembayaran : <br><span
                                                        class="text-danger">{{ $item->expired_date_formatted }}</span></small>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <small class="d-block text-center">
                                                    @if ($item->tanggal_pembayaran == null)
                                                        Belum melakukan pembayaran
                                                    @else
                                                        {{ $item->tanggal_pembayaran_formatted }}
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-center d-none">
                                                <button class="btn custom-btn-outline" disabled>
                                                    <small>Upload Bukti Pembayaran</small>
                                                </button>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <form action="{{ route('upload-bukti-pembayaran', ['id' => $item->id]) }}"
                                                    wire: method="POST" id="form_bukti_pembayaran"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @if ($item->bukti_pembayaran == null)
                                                        <label for="bukti_pembayaran_{{ $item->id }}"
                                                            class="btn custom-btn-outline">
                                                            <small>Upload Bukti Pembayaran</small>
                                                        </label>
                                                    @else
                                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}"
                                                            target="_blank" class="btn custom-btn-outline">
                                                            <small>Lihat Bukti Pembayaran</small>
                                                        </a>
                                                    @endif
                                                    <input type="file" id="bukti_pembayaran_{{ $item->id }}"
                                                        data-id="{{ $item->id }}" name="bukti_pembayaran"
                                                        class="form-control bukti-pembayaran"
                                                        accept="image/jpg, image/jpeg, image/png, .pdf" hidden>
                                                    <div class="d-none text-center" id="sub_form_{{ $item->id }}">
                                                        <img src="" id="output_{{ $item->id }}"
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
                        <div class="tab-pane fade py-4" id="generate_invoice">
                            @if (count($listGenerateInvoice))
                                @foreach ($listGenerateInvoice as $item)
                                    <div class="bg-white shadow border p-4 mb-3" style="border-radius: 15px">
                                        <div class="row">
                                            <div
                                                class="col-md-4 d-flex d-md-block justify-content-between align-items-center">
                                                <div class="fw-bold text-start"> <a
                                                        href="{{ route('detail-packages', ['id' => $item->paket->id ?? 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->paket->nama ?? 'Paket dihapus' }}
                                                    </a></div>
                                                <small
                                                    class="d-block text-md-start text-center">{{ $item->paket->jumlah_pertemuan }}
                                                    Pertemuan</small>
                                            </div>
                                            <div class="col-md-4 text-center mt-2 mt-md-0">
                                                <div class="fw-bold text-center">{{ $item->harga_formatted }}</div>
                                                <small class="d-block text-center">Diskon
                                                    {{ $item->paket->diskon }}%</small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="text-center">
                                                    <small>Code : {{ $item->unix_code ?? '-' }}</small>
                                                    <a href="{{ route('profile.invoice', ['id' => $item->id]) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Invoice" type="button">
                                                        <i class="fa-solid fa-file-invoice"></i>
                                                    </a>
                                                </div>
                                                @if ($item->status == 1)
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="fs-2 text-success mx-1"><i
                                                                class="fa-regular fa-circle-check"></i></span>
                                                        <span class="badge text-bg-success mx-1">Lunas</span>
                                                    </div>
                                                @elseif($item->status == 2)
                                                    <div class="text-center">
                                                        <span class="badge text-bg-info text-white">Belum Lunas</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-4 align-items-center justify-content-between">
                                            <div class="col-md-4 fw-bold text-md-start text-center">Pembayaran</div>
                                            <div class="col-md-4 text-center mt-2 mt-md-0 mb-2 mb-md-0">
                                                <small class="d-block text-center">
                                                    @if ($item->tanggal_pembayaran == null)
                                                        Belum melakukan pembayaran
                                                    @else
                                                        <div>{{ $item->tanggal_pembayaran_formatted }}</div>
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-center d-none">
                                                <button class="btn custom-btn-outline" disabled>
                                                    <small>Upload Bukti Pembayaran</small>
                                                </button>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <form action="{{ route('upload-bukti-pembayaran', ['id' => $item->id]) }}"
                                                    wire: method="POST" id="form_bukti_pembayaran"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @if ($item->bukti_pembayaran == null)
                                                        <label for="bukti_pembayaran_{{ $item->id }}"
                                                            class="btn custom-btn-outline">
                                                            <small>Upload Bukti Pembayaran</small>
                                                        </label>
                                                    @else
                                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}"
                                                            target="_blank" class="btn custom-btn-outline">
                                                            <small>Lihat Bukti Pembayaran</small>
                                                        </a>
                                                    @endif
                                                    <input type="file" id="bukti_pembayaran_{{ $item->id }}"
                                                        data-id="{{ $item->id }}" name="bukti_pembayaran"
                                                        class="form-control bukti-pembayaran"
                                                        accept="image/jpg, image/jpeg, image/png, .pdf" hidden>
                                                    <div class="d-none text-center" id="sub_form_{{ $item->id }}">
                                                        <img src="" id="output_{{ $item->id }}"
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
                        <div class="tab-pane fade py-4" id="transaction_history">
                            @if (count($listTransactionHistory))
                                @foreach ($listTransactionHistory as $index => $item)
                                    <div class="bg-white shadow border p-4 mb-3" style="border-radius: 15px">
                                        <div class="row">
                                            <div
                                                class="col-md-4 d-flex d-md-block justify-content-between align-items-center">
                                                <div class="fw-bold text-start"> <a
                                                        href="{{ route('detail-packages', ['id' => $item->paket->id ?? 0]) }}"
                                                        style="text-decoration: none; color: black">
                                                        {{ $item->paket->nama ?? 'Paket dihapus' }}
                                                    </a></div>
                                                <small
                                                    class="d-block text-md-start text-center">{{ $item->paket->jumlah_pertemuan }}
                                                    Pertemuan</small>
                                            </div>
                                            <div class="col-md-4 text-center mt-2 mt-md-0">
                                                <div class="fw-bold text-center">{{ $item->harga_formatted }}</div>
                                                <small class="d-block text-center">Harga sudah di potonga diskon
                                                    <br></small>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="text-center">
                                                    <small>Code : {{ $item->unix_code ?? '-' }}</small>
                                                    <a href="{{ route('profile.invoice', ['id' => $item->id]) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Invoice" type="button">
                                                        <i class="fa-solid fa-file-invoice"></i>
                                                    </a>
                                                </div>
                                                @if ($item->status == 1)
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="fs-2 text-success mx-1"><i
                                                                class="fa-regular fa-circle-check"></i></span>
                                                        <span class="badge text-bg-success mx-1">Lunas</span>
                                                    </div>
                                                @elseif($item->status == 2)
                                                    <div class="text-center">
                                                        <span class="badge text-bg-info text-white">Belum Lunas</span>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="row mt-4 align-items-center justify-content-between">
                                            <div class="col-md-4 fw-bold text-md-start text-center">Pembayaran</div>
                                            <div class="col-md-4 text-center mt-2 mt-md-0 mb-2 mb-md-0">
                                                <small class="d-block text-center">
                                                    @if ($item->tanggal_pembayaran == null)
                                                        Belum melakukan pembayaran
                                                    @else
                                                        {{ $item->tanggal_pembayaran_formatted }}
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-4 text-center d-none">
                                                <button class="btn custom-btn-outline" disabled>
                                                    <small>Upload Bukti Pembayaran</small>
                                                </button>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <form action="{{ route('upload-bukti-pembayaran', ['id' => $item->id]) }}"
                                                    wire: method="POST" id="form_bukti_pembayaran"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @if ($item->bukti_pembayaran == null)
                                                        <label for="bukti_pembayaran_{{ $item->id }}"
                                                            class="btn custom-btn-outline">
                                                            <small>Upload Bukti Pembayaran</small>
                                                        </label>
                                                    @else
                                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}"
                                                            target="_blank" class="btn custom-btn-outline">
                                                            <small>Lihat Bukti Pembayaran</small>
                                                        </a>
                                                    @endif
                                                    <input type="file" id="bukti_pembayaran_{{ $item->id }}"
                                                        data-id="{{ $item->id }}" name="bukti_pembayaran"
                                                        class="form-control bukti-pembayaran"
                                                        accept="image/jpg, image/jpeg, image/png, .pdf" hidden>
                                                    <div class="d-none text-center" id="sub_form_{{ $item->id }}">
                                                        <img src="" id="output_{{ $item->id }}"
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

@push('js')
    <script>
        $(document).ready(function() {
            $('.bukti-pembayaran').each(function(index) {
                const id = $(this).data('id');
                $('#bukti_pembayaran_' + id).on('change', function(event) {
                    const id = $(this).data('id')
                    var image = document.getElementById('output_' + id);
                    image.src = URL.createObjectURL(event.target.files[0]);
                    $('#sub_form_' + id).removeClass('d-none')
                })
            })
        });
    </script>
@endpush
