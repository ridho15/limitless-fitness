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
                        <div class="mb-3 text-center fw-bold h5">MEMBERSHIP AGREMENT</div>
                        <div class="mb-3">
                            {{ $user->location->alamat }} do here by agree Rp.{{ number_format($paket->harga_diskon,0,',','.') }} for {{ $paket->nama }}
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Membership Name
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $paket->nama }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Membership Price
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($paket->harga_diskon,0,',','.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Price Per Additional Member
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($paket->price_per_additional,0,',','.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Additional Member Limit
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $paket->additional_member }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Signup Fee
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($paket->signup_fee,0,',','.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Pro Rata
                            </div>
                            <div class="col-md col">
                                : <strong></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Start Date
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $start_date }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                End Date
                            </div>
                            <div class="col-md col">
                                : <strong>{{ $end_date }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Potongan Voucher
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($potongan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Biaya Penanganan
                            </div>
                            <div class="col-md col">
                                : <strong>Rp.{{ number_format($unixcode, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-6">
                                Total
                            </div>
                            <div class="col-md col h5">
                                :
                                <strong
                                    class="text-danger">Rp.{{ number_format($paket->harga_diskon - $potongan + $unixcode, 0, ',', '.') }}</strong>
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
                            <ol>
                                @foreach ($termsConditions as $index => $item)
                                    <li class="text-uppercase"><span class="fw-bold">{{ $index }}</span>
                                        <ul>
                                            @foreach ($item as $index => $item2)
                                                <li>{{ $item2 }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ol>
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
