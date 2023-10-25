<div>
    <div class="px-4 py-5 bg-white shadow sticky-top" style="border-radius: 15px; top: 80px; z-index: 2 !important;">
        <small class="d-block mb-2 fw-semibold text-uppercase text-secondary">{{ $paket->kategori_formatted }}</small>
        <h5 class="fw-semibold mb-3">{{ $paket->nama }}</h5>
        <div class="d-flex align-items-center justify-content-between">
            <small class="fw-light">{{ $paket->location_formatted }} access</small>
            <small class="fw-light">{{ $paket->jumlah_pertemuan }} credits</small>
        </div>
        <hr class="my-4" style="opacity: 0.1">
        <div class="fw-semibold mb-3">Price detail</div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="fw-light">Standard</small>
            <small class="fw-light">Rp.{{ number_format($paket->harga, 0, ',', '.') }}</small>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <small class="fw-light">Diskon ({{ $paket->diskon }}%)</small>
            <small class="fw-light">Rp.{{ number_format(($paket->harga * $paket->diskon) / 100, 0, ',', '.') }}</small>
        </div>
        <hr class="my-4" style="opacity: 0.1">
        <div class="d-flex justify-content-between align-items-center">
            <small class="fw-light">Subtotal</small>
            <small class="fw-light">Rp.{{ number_format($subtotal, 0, ',', '.') }}</small>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <small class="fw-light">Potongan Voucher</small>
            <small class="fw-light">Rp.{{ number_format($potongan, 0, ',', '.') }}</small>
        </div>
        <hr class="my-4" style="opacity: 0.1">
        <div class="d-flex justify-content-between align-items-center">
            <small class="fw-bold">Grand Total</small>
            <small class="fw-bold">Rp.{{ number_format($subtotal - $potongan, 0, ',', '.') }}</small>
        </div>
    </div>
    <div class="px-4 mt-3 py-5 bg-white shadow sticky-top"
        style="border-radius: 15px; top: 80px; z-index: 2 !important;">
        @include('helper.alert-message')
        <div class="form-group">
            <label for="" class="form-label">Kode Voucher</label>
            <div class="input-group mb-3">
                <input type="text" name="kode_voucher" wire:model="kode_voucher"
                    class="form-control padding-vertical-12" placeholder="Masukkan kode voucher"
                    @if ($isGunakan == true) disabled @endif aria-label="Masukkan kode voucher"
                    aria-describedby="basic-addon2">
                <span class="input-group-text @if ($isGunakan == false) bg-color-custom text-white @endif"
                    style="cursor: pointer" id="basic-addon2" wire:click="checkKodeVoucher">Check</span>
            </div>
            @error('kode_voucher')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        @if ($isCheck == true && $isGunakan == false)
            <div class="d-flex align-items-center justify-content-center">
                <button type="button" wire:click="batalkanVoucher"
                    class="btn custom-btn-outline me-1">Batalkan</button>
                <button type="button" wire:click="gunakanKodeVoucher" class="btn custom-btn ms-1">Gunakan</button>
            </div>
        @endif
    </div>
</div>
