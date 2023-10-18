@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md-9 ps-5">
                    <h4 class="fw-bold">My Voucher</h4>
                    <ul class="nav nav-underline">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#semua">Semua</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#belum_digunakan">Belum Digunakan</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#sudah_digunakan">Sudah Digunakan</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 custom-nav-link" role="tab" data-bs-toggle="tab"
                                href="#expired">Expired</a>
                        </li>
                    </ul>
                    <hr>
                    @if (session()->has('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    <div class="tab-content">
                        <div class="tab-pane fade py-4 show active" id="semua">
                            <h4 class="fw-bold">Semua</h4>
                            <div class="row"></div>
                        </div>
                        <div class="tab-pane fade py-4" id="belum_digunakan">
                            <h4 class="fw-bold">Belum Digunakan</h4>
                            <div class="row"></div>
                        </div>
                        <div class="tab-pane fade py-4" id="sudah_digunakan">
                            <h4 class="fw-bold">Sudah Digunakan</h4>
                            <div class="row"></div>
                        </div>
                        <div class="tab-pane fade py-4" id="expired">
                            <h4 class="fw-bold">Expired</h4>
                            <div class="row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
