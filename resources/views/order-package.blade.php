@extends('layouts.public')

@section('title', 'Pesan Paket - ' . $paket->nama)

@push('styles')
<style>
    .primary-bg { background-color: #000000; }
    .primary-text { color: #000000; }
    .primary-border { border-color: #000000; }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        border-radius: 16px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        border: 1px solid #404040;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .modal-overlay.show .modal-content {
        transform: scale(1);
    }

    /* Alert Styles */
    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1001;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }

    .alert.show {
        transform: translateX(0);
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981, #059669);
        border-left: 4px solid #10b981;
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-left: 4px solid #ef4444;
    }

    .alert-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-left: 4px solid #f59e0b;
    }
</style>
@endpush

@section('content')
    <!-- Alert Messages -->
    @if(session('success'))
        <div id="alert-success" class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="alert-error" class="alert alert-error">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div id="alert-validation" class="alert alert-error">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal-overlay">
        <div class="modal-content">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-question text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Pemesanan</h3>
                <p class="text-gray-300 mb-6">Apakah Anda yakin ingin memesan paket <strong class="text-white">{{ $paket->nama }}</strong>?</p>

                <div class="flex space-x-3">
                    <button id="confirmBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-check mr-2"></i>Ya, Pesan Sekarang
                    </button>
                    <button id="cancelBtn" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="gradient-overlay py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center">
                <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight">
                    PESAN PAKET FITNESS
                </h1>
                <div class="w-24 h-1 bg-white mx-auto"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl overflow-hidden border border-gray-800 shadow-2xl">
            <!-- Package Header -->
            <div class="bg-gradient-to-r from-black to-gray-900 p-8 border-b border-gray-800">
                <h2 class="text-3xl font-bold text-white mb-4">{{ $paket->nama }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-400 mb-2">{{ $paket->deskripsi ?? 'Deskripsi paket tidak tersedia' }}</p>
                        @if($paket->valid_month)
                            <div class="flex items-center text-gray-300 mt-4">
                                <i class="fas fa-calendar-alt text-white mr-2"></i>
                                <span>Validitas: {{ $paket->valid_month }} Bulan</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($paket->diskon > 0)
                            <div class="mb-2">
                                <span class="bg-red-600 text-white text-sm font-bold px-4 py-2 rounded-full">
                                    -{{ $paket->diskon }}%
                                </span>
                            </div>
                            <p class="text-gray-500 line-through text-lg">Rp {{ number_format($paket->harga ?? 0, 0, ',', '.') }}</p>
                            <p class="text-4xl font-black text-white">Rp {{ number_format($paket->harga_diskon ?? $paket->harga, 0, ',', '.') }}</p>
                        @else
                            <p class="text-4xl font-black text-white">Rp {{ number_format($paket->harga ?? 0, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            <div class="p-8">
                <form id="orderForm" action="{{ route('post-order-package', $paket->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-white mb-2">
                                <i class="fas fa-user mr-2"></i>Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="nama" required
                                   class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent placeholder-gray-500"
                                   placeholder="Masukkan nama lengkap Anda"
                                   value="{{ old('nama') }}">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-white mb-2">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent placeholder-gray-500"
                                   placeholder="email@example.com"
                                   value="{{ old('email') }}">
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Jika email sudah terdaftar, sistem akan menggunakan akun tersebut
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-phone mr-2"></i>Nomor HP
                        </label>
                        <input type="tel" id="no_hp" name="no_hp" required
                               class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent placeholder-gray-500"
                               placeholder="08xxxxxxxxxx"
                               value="{{ old('no_hp') }}">
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>Alamat Lengkap
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" required
                                  class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent placeholder-gray-500"
                                  placeholder="Masukkan alamat lengkap Anda">{{ old('alamat') }}</textarea>
                    </div>

                    <div>
                        <label for="catatan" class="block text-sm font-semibold text-white mb-2">
                            <i class="fas fa-comment mr-2"></i>Catatan Tambahan (Opsional)
                        </label>
                        <textarea id="catatan" name="catatan" rows="3"
                                  class="w-full px-4 py-3 bg-gray-800 text-white border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent placeholder-gray-500"
                                  placeholder="Pesan khusus atau permintaan tambahan...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="pt-4 space-y-4">
                        <button type="button" id="submitBtn" class="w-full bg-white text-black py-4 px-6 rounded-xl hover:bg-gray-200 transition-all duration-200 font-bold text-center uppercase tracking-wide shadow-lg hover:shadow-xl">
                            <i class="fas fa-shopping-cart mr-2"></i> Pesan Sekarang
                        </button>

                        <a href="{{ route('packages') }}" class="w-full block text-center bg-gray-800 text-white py-4 px-6 rounded-xl hover:bg-gray-700 transition-all duration-200 font-semibold border border-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Paket
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('orderForm');
    const submitBtn = document.getElementById('submitBtn');
    const modal = document.getElementById('confirmationModal');
    const confirmBtn = document.getElementById('confirmBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    // Show alerts if they exist
    const successAlert = document.getElementById('alert-success');
    const errorAlert = document.getElementById('alert-error');
    const validationAlert = document.getElementById('alert-validation');

    if (successAlert) {
        successAlert.classList.add('show');
        setTimeout(() => {
            successAlert.classList.remove('show');
        }, 5000);
    }

    if (errorAlert) {
        errorAlert.classList.add('show');
        setTimeout(() => {
            errorAlert.classList.remove('show');
        }, 5000);
    }

    if (validationAlert) {
        validationAlert.classList.add('show');
        setTimeout(() => {
            validationAlert.classList.remove('show');
        }, 7000);
    }

    // Submit button click - show confirmation modal
    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();

        // Basic form validation
        const nama = document.getElementById('nama').value.trim();
        const email = document.getElementById('email').value.trim();
        const noHp = document.getElementById('no_hp').value.trim();
        const alamat = document.getElementById('alamat').value.trim();

        if (!nama || !email || !noHp || !alamat) {
            showAlert('Mohon lengkapi semua field yang wajib diisi!', 'error');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showAlert('Format email tidak valid!', 'error');
            return;
        }

        // Show confirmation modal
        modal.classList.add('show');
    });

    // Confirm button - submit form
    confirmBtn.addEventListener('click', function() {
        modal.classList.remove('show');
        form.submit();
    });

    // Cancel button - hide modal
    cancelBtn.addEventListener('click', function() {
        modal.classList.remove('show');
    });

    // Close modal when clicking overlay
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    // Function to show temporary alerts
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            ${message}
        `;

        document.body.appendChild(alertDiv);

        // Trigger animation
        setTimeout(() => alertDiv.classList.add('show'), 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
});
</script>
@endpush