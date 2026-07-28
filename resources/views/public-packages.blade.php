@extends('layouts.public')

@section('title', 'Limitless Fitness - Pilih Paket')

@section('content')
    <!-- Hero Section -->
    <div class="gradient-overlay py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <!-- Title Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight">
                    PILIH PAKET FITNESS ANDA
                </h1>
                <div class="w-24 h-1 bg-white mx-auto mb-6"></div>
                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
                    Transformasi dimulai dari keputusan yang tepat. Pilih paket yang sesuai dengan tujuan fitness Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($pakets as $paket)
            <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl overflow-hidden border border-gray-800 card-hover shadow-2xl">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-black to-gray-900 p-6 border-b border-gray-800">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-2xl font-bold text-white leading-tight flex-1">{{ $paket->nama }}</h3>
                        @if($paket->diskon > 0)
                            <span class="bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full ml-2 whitespace-nowrap">
                                -{{ $paket->diskon }}%
                            </span>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        @if($paket->diskon > 0)
                            <div class="flex flex-col">
                                <span class="text-gray-500 line-through text-sm mb-1">{{ $paket->harga_formatted }}</span>
                                <div class="flex items-baseline">
                                    <span class="text-4xl font-black text-white">Rp{{ number_format($paket->harga_diskon, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @else
                            <span class="text-4xl font-black text-white">{{ $paket->harga_formatted }}</span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <!-- Package Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center text-gray-300">
                            <div class="w-10 h-10 bg-white bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Validitas</p>
                                <p class="text-white font-semibold">{{ $paket->valid_month }} Bulan</p>
                            </div>
                        </div>

                        <div class="flex items-start text-gray-300">
                            <div class="w-10 h-10 bg-white bg-opacity-10 rounded-lg flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Akses Kelas</p>
                                <div class="space-y-1">
                                    @foreach($paket->kelas->take(3) as $kelas)
                                        <p class="text-white text-sm font-medium">• {{ $kelas->nama_kelas }}</p>
                                    @endforeach
                                    @if($paket->kelas->count() > 3)
                                        <p class="text-gray-400 text-sm">+{{ $paket->kelas->count() - 3 }} kelas lainnya</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <a href="{{ route('order-package', $paket->id) }}"
                       class="w-full bg-white text-black py-4 px-6 rounded-xl hover:bg-gray-200 transition-all duration-200 font-bold text-center block uppercase tracking-wide shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Pilih Paket Ini
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($pakets->isEmpty())
        <div class="text-center py-20">
            <div class="inline-block p-8 bg-gray-900 rounded-2xl border border-gray-800">
                <i class="fas fa-box-open text-6xl text-gray-700 mb-6"></i>
                <h3 class="text-2xl font-bold text-white mb-3">Belum Ada Paket Tersedia</h3>
                <p class="text-gray-400">Silakan kembali lagi nanti untuk melihat paket terbaru kami.</p>
            </div>
        </div>
        @endif
    </div>
@endsection