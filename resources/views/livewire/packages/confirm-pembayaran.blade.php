<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalConfirmPembayaran" tabindex="-1"
        aria-labelledby="modalConfirmPembayaran" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalConfirmPembayaran">Confirm Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" wire:submit.prevent='konfirmasiPembayaran' method="POST">
                    @csrf
                    <div class="modal-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session()->get('success') }}
                            </div>
                        @elseif(session()->has('fail'))
                            <div class="alert alert-danger" role="alert">
                                {{ session()->get('fail') }}
                            </div>
                        @endif
                        <div class="" style="max-height: 200px !important; overflow: auto">
                            <div class="px-2 py-2" style="border: 1px solid gray; border-radius: 20px;">
                                @if (count($listVoucher) > 0)
                                    @foreach ($listVoucher as $index => $item)
                                        <div class="row justify-content-center align-items-center">
                                            <div class="col-md col mb-2">
                                                <img src="{{ asset('assets/images/no_image.png') }}" alt=""
                                                    height="75" width="75" style="object-fit: cover">
                                            </div>
                                            <div class="col-md col mb-2">
                                                <small class="text-sm"><strong>{{ $item->voucher->nama }} </strong><br>
                                                    {{ $item->voucher->deskripsi }}
                                                </small>
                                            </div>
                                            <div class="col-md col mb-2">
                                                <small class="text-sm text-danger fw-bold">Expired Date
                                                    <br>{{ date('d-m-Y', strtotime($item->expired)) }}</small>
                                            </div>
                                            <div class="col-md col mb-2">
                                                <small class="text-sm">
                                                    {{ $item->voucher->avaiable_voucher }}
                                                </small>
                                            </div>
                                            <div class="col-md col mb-2 text-center">
                                                <button type="button" wire:click="gunakanVoucher({{ $item->id }})"
                                                    class="btn custom-btn"
                                                    @if ($id_voucher_customer == $item->id) disabled @endif>Gunakan</button>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                @else
                                    <div class="text-sm text-center">
                                        <small>Tidak ada voucher</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            Kamu akan melakukan pembayaran pada:</div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Paket
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $paket->nama }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Paket
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $paket->nama }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Harga
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($paket->harga_diskon, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Potongan
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($potongan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Total
                            </div>
                            <div class="col-md col">
                                :
                                <strong
                                    class="text-danger">Rp.{{ number_format($paket->harga_diskon - $potongan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="my-3">
                            <span class="d-block mb-2">Silahkan Kirim Pembayaran Ke Rekening : </span>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    Bank
                                </div>
                                <div class="col-md col">
                                    : <strong>{{ $bank ?? 'Bank Central Asia' }}</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    Nomor Rekening
                                </div>
                                <div class="col-md col">
                                    : <strong>{{ $no_rekening ?? '6600-4829-11' }}</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-3">
                                    Atas Nama
                                </div>
                                <div class="col-md col">
                                    : <strong>{{ $atas_nama ?? 'Gerald Reyner Liando' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="my-3">
                            Setelah melakukan pembayaran, diharapkan untuk melakukan confirmasi dan mengirimkan bukti
                            pembayaran pada admin.
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                                required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Apakah kamu menerima syarat dan ketentuan yang berlaku
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
</div>

@push('js')
    <script></script>
@endpush
