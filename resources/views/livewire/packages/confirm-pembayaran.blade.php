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
                                Potongan Voucher
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($potongan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-3">
                                Total
                            </div>
                            <div class="col-md col h5">
                                :
                                <strong class="text-danger">Rp.{{ number_format($paket->harga_diskon - $potongan, 0,
                                    ',', '.') }}</strong>
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
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                Apakah kamu menerima syarat dan ketentuan yang berlaku
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom-btn-white" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom-btn" @if (!Auth::user()) disabled
                            @endif>Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script></script>
@endpush