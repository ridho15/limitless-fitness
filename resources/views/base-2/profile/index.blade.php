@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
                <div class="col-md-9 ps-5">
                    <div class="row mb-4">
                        <div class="col-md-6 col-6 text-start">
                            <h3 class="fw-bold">My Profile</h3>
                        </div>
                        <div class="col-md-6 col-6 text-end">
                            <button type="button" class="btn custom-btn-outline" data-bs-toggle="collapse"
                                data-bs-target="#collapseFormProfile" aria-expanded="false"
                                aria-controls="collapseFormProfile" id="btn_edit_profile">Edit Profile</button>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-4">Account Information</h5>
                    <div class="collapse mb-5" id="collapseFormProfile">
                        <div class="card card-body bg-white shadow-sm" style="border-radius: 15px">
                            @livewire('form-profile')
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Name</small>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Mobile Number</small>
                            <div class="fw-semibold">{{ $user->no_hp }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Date Of Birth</small>
                            <div class="fw-semibold">
                                {{ $user->tanggal_lahir ? $user->tanggal_lahir_formatted : '-- -- --' }}</div>
                        </div>
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Email Address</small>
                            <div class="fw-semibold" style="overflow: auto">{{ $user->email }}</div>
                            <small class="fw-light d-block">Communications and transactions history will be sent to this
                                email address</small>
                            <a href="{{ route('profile.resend-verifikasi-email', ['id' => $user->id]) }}"
                                style="text-decoration: none"><small>Verified At
                                    {{ $user->email_verified_at }}</small></a>
                        </div>
                    </div>
                    <div class="text-center">
                        <img src="{{ 'data:image/png;base64,' . $generatorBarcode->getBarcodePNG($user->barcode, 'EAN8') }}"
                            alt="barcode" style="height: 50px" />
                            <div class="text-center">
                                {{ $user->barcode }}
                            </div>
                    </div>
                    <div class="text-center">
                        <img src="{{ (new chillerlan\QRCode\QRCode())->render($kode) }}" alt=""
                            style="width: 300px; height: 300px; object-fit: contain;">
                        <div class="text-center mt-3">
                            <small>Scan qr code to check in and check out</small>
                        </div>
                    </div>
                    <hr class="my-5">
                    <h5 class="fw-bold mb-4">
                        Personal information
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Gender</small>
                            <div class="fw-semibold">{{ $user->jenis_kelamin_formatted }}</div>
                        </div>
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Country</small>
                            <div class="fw-semibold">{{ $user->negara ?? '-- --' }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Home Address</small>
                            <div class="fw-semibold">{{ $user->alamat ?? '-- --' }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Foto KTP</small>
                            <div class="text-center">
                                @if($user->ktp)
                                    <img src="{{ asset('storage/' . $user->ktp) }}" alt="Foto KTP" class="img-fluid rounded" style="max-width: 200px; max-height: 150px; object-fit: contain;">
                                @else
                                    <div class="border border-secondary rounded p-3 text-muted" style="width: 200px; height: 150px; display: flex; align-items: center; justify-content: center;">
                                        <small>Belum di-upload</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Foto Selfie</small>
                            <div class="text-center">
                                @if($user->selfie)
                                    <img src="{{ asset('storage/' . $user->selfie) }}" alt="Foto Selfie" class="img-fluid rounded" style="max-width: 200px; max-height: 150px; object-fit: contain;">
                                @else
                                    <div class="border border-secondary rounded p-3 text-muted" style="width: 200px; height: 150px; display: flex; align-items: center; justify-content: center;">
                                        <small>Belum di-upload</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr class="my-5">
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

        });

        $('#btn_edit_profile').on('click', function() {})
    </script>
@endpush
