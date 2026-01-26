@extends('layouts.public')

@section('title', 'Pembayaran Pesanan')

@push('styles')
<style>
    .payment-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .payment-card.active {
        border-color: #ffffff;
        background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
    }

    .alert-public {
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #34d399;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #f87171;
    }

    .alert-info {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.4);
        color: #93c5fd;
    }
</style>
@endpush

@section('content')
    <div class="gradient-overlay py-12 px-4">
        <div class="container mx-auto max-w-6xl text-center">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight">
                PILIH METODE PEMBAYARAN
            </h1>
            <p class="text-gray-300 max-w-3xl mx-auto">Pesanan Anda telah kami terima. Silakan pilih metode pembayaran yang sesuai. Anda dapat mengubah pilihan ini kapan saja sebelum pembayaran dikonfirmasi.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3 space-y-6">
                @if(session('success'))
                    <div class="alert-public alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert-public alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert-public alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-public alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong class="block mb-1">Mohon periksa kembali:</strong>
                            <ul class="list-disc list-inside text-sm space-y-1 text-gray-200">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-2xl p-8 shadow-2xl">
                    <form id="payment-method-form" action="{{ route('order-payment.process', $order->public_token) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <label class="payment-card block rounded-xl p-5 cursor-pointer {{ $order->payment_method === 'manual' || !$order->payment_method ? 'active' : '' }}" data-target="manual">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <span class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-check text-white text-xl"></i>
                                        </span>
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h3 class="text-xl font-semibold text-white">Verifikasi Manual</h3>
                                                <span class="text-xs px-2 py-1 bg-gray-800 rounded-full text-gray-300">Rekomendasi untuk konsultasi</span>
                                            </div>
                                            <p class="text-gray-400 text-sm mt-1">Tim Limitless Fitness akan menghubungi Anda untuk menyelesaikan pembayaran secara langsung.</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="payment_method" value="manual" class="w-5 h-5" {{ $order->payment_method === 'manual' || !$order->payment_method ? 'checked' : '' }}>
                                </div>
                            </label>

                            <label class="payment-card block rounded-xl p-5 cursor-pointer {{ $order->payment_method === 'transfer' ? 'active' : '' }}" data-target="transfer">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <span class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center">
                                            <i class="fas fa-money-check-alt text-white text-xl"></i>
                                        </span>
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h3 class="text-xl font-semibold text-white">Transfer Bank</h3>
                                                <span class="text-xs px-2 py-1 bg-white text-black rounded-full">Cepat & Mandiri</span>
                                            </div>
                                            <p class="text-gray-400 text-sm mt-1">Transfer ke rekening resmi Limitless Fitness dengan kode unik untuk verifikasi otomatis.</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="payment_method" value="transfer" class="w-5 h-5" {{ $order->payment_method === 'transfer' ? 'checked' : '' }}>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-white text-black py-4 px-6 rounded-xl hover:bg-gray-200 transition-all duration-200 font-bold uppercase tracking-wide">
                            <i class="fas fa-save mr-2"></i> Simpan Pilihan Metode
                        </button>
                    </form>

                    <p class="text-xs text-gray-500 mt-4">
                        Dengan memilih metode pembayaran, Anda menyetujui ketentuan transaksi Limitless Fitness. Anda dapat mengubah pilihan ini sebelum melakukan pembayaran.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-gradient-to-br from-black to-gray-900 border border-gray-800 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Ringkasan Pesanan</h2>

                    <div class="space-y-4 text-gray-300">
                        <div class="flex justify-between">
                            <span>ID Pesanan</span>
                            <span>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Nama Pemesan</span>
                            <span>{{ $order->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email</span>
                            <span>{{ $order->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Nomor HP</span>
                            <span>{{ $order->user->no_hp ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 my-6"></div>

                    <div class="space-y-2 text-gray-300">
                        <div class="flex justify-between">
                            <span>Paket</span>
                            <span class="font-semibold text-white text-right">
                                {{ $order->paket->nama }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Harga Paket</span>
                            <span>Rp {{ number_format((int) $order->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kode Unik</span>
                            <span>{{ $order->unix_code ? (int) $order->unix_code : '-' }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-white text-lg">
                            <span>Total Transfer</span>
                            <span>Rp {{ number_format($transferTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-800/60 rounded-xl text-sm text-gray-300">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-white text-black flex items-center justify-center font-bold">i</span>
                            <div>
                                <p>Status Pembayaran: <span class="font-semibold text-white">{{ $order->status_formatted }}</span></p>
                                <p class="mt-1">Metode Saat Ini: <span class="font-semibold text-white">{{ $order->payment_method ? ucfirst($order->payment_method) : 'Belum dipilih' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="manualInstructions" class="bg-yellow-500/10 border border-yellow-500/40 rounded-2xl p-6 text-yellow-100 {{ $order->payment_method === 'transfer' ? 'hidden' : '' }}">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-headset text-2xl"></i>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Instruksi Verifikasi Manual</h3>
                            <p class="text-sm leading-relaxed">{{ data_get($paymentConfig, 'manual_note', 'Tim Limitless Fitness akan menghubungi Anda melalui WhatsApp atau email untuk mengatur pembayaran dan aktivasi paket.') }}</p>
                            <p class="text-sm leading-relaxed mt-3">Pastikan nomor telepon dan email Anda aktif agar kami dapat menghubungi Anda tanpa kendala.</p>
                        </div>
                    </div>
                </div>

                <div id="transferInstructions" class="bg-emerald-500/10 border border-emerald-500/40 rounded-2xl p-6 text-emerald-100 {{ $order->payment_method === 'transfer' ? '' : 'hidden' }}">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-university text-2xl"></i>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-4">Instruksi Transfer Bank</h3>
                            <div class="bg-black/60 border border-emerald-500/30 rounded-xl p-4 text-sm space-y-3">
                                <div class="flex justify-between">
                                    <span>Bank</span>
                                    <span class="font-semibold">{{ data_get($paymentConfig, 'transfer.bank_name', '-') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Nomor Rekening</span>
                                    <span class="font-semibold">{{ data_get($paymentConfig, 'transfer.account_number', '-') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Atas Nama</span>
                                    <span class="font-semibold">{{ data_get($paymentConfig, 'transfer.account_name', '-') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total yang Harus Dibayar</span>
                                    <span class="font-bold">Rp {{ number_format($transferTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <p class="text-sm leading-relaxed mt-4">{{ data_get($paymentConfig, 'transfer.note', 'Setelah transfer, simpan bukti pembayaran dan pastikan jumlahnya sesuai total di atas. Kami akan menghubungi Anda untuk verifikasi selanjutnya.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.payment-card');
        const manualInstructions = document.getElementById('manualInstructions');
        const transferInstructions = document.getElementById('transferInstructions');

        cards.forEach(card => {
            card.addEventListener('click', function () {
                cards.forEach(item => item.classList.remove('active'));
                card.classList.add('active');

                const radio = card.querySelector('input[type="radio"]');
                radio.checked = true;
                toggleInstructions(card.getAttribute('data-target'));
            });
        });

        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function () {
                cards.forEach(item => item.classList.remove('active'));
                const parentCard = radio.closest('.payment-card');
                if (parentCard) {
                    parentCard.classList.add('active');
                    toggleInstructions(parentCard.getAttribute('data-target'));
                }
            });
        });

        const activeCard = document.querySelector('.payment-card.active');
        if (activeCard) {
            toggleInstructions(activeCard.getAttribute('data-target'));
        }

        function toggleInstructions(target) {
            if (target === 'manual') {
                manualInstructions.classList.remove('hidden');
                transferInstructions.classList.add('hidden');
            } else {
                manualInstructions.classList.add('hidden');
                transferInstructions.classList.remove('hidden');
            }
        }
    });
</script>
@endpush
