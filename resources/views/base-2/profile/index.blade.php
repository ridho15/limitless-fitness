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
                            <form action="{{ route('updateProfile') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            value="{{ $user->name }}" style="border-radius: 10px" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="{{ $user->email }}" aria-describedby="emailHelp"
                                            style="border-radius: 10px" required>
                                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mobile_number" class="form-label">Mobile Number</label>
                                        <input type="number" name="no_hp" class="form-control" id="mobile_number"
                                            value="{{ $user->no_hp }}" style="border-radius: 10px" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            style="border-radius: 10px">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir"
                                            value="{{ $user->tanggal_lahir }}" style="border-radius: 10px">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Jenis Kelamin</label>
                                        <div class="d-flex align-items-center justify-content-evenly">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="1"
                                                    id="pria" @if ($user->jenis_kelamin == 1) checked @endif>
                                                <label class="form-check-label" for="pria">
                                                    Pria
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="0"
                                                    id="wanita" @if ($user->jenis_kelamin == 0) checked @endif>
                                                <label class="form-check-label" for="wanita">
                                                    Wanita
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="negara">Negara</label>
                                        <input type="text" name="negara" class="form-control" id="negara" required style="border-radius: 10px">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" class="form-control" id="alamat" style="border-radius: 10px">{{ $user->alamat }}</textarea>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn custom-btn"><i class="fa-solid fa-floppy-disk"></i>
                                        Simpan</button>
                                </div>
                            </form>
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
                            <div class="fw-semibold">{{ $user->tanggal_lahir ? $user->tanggal_lahir_formatted : '-- -- --' }}</div>
                        </div>
                        <div class="col-md-6 col-6">
                            <small class="fw-light d-block">Email Address</small>
                            <div class="fw-semibold">{{ $user->email }}</div>
                            <small class="fw-light d-block">Communications and transactions history will be sent to this
                                email address</small>
                            <a href="{{ route('profile.resend-verifikasi-email', ['id' => $user->id]) }}" style="text-decoration: none"><small>Verified At {{ $user->email_verified_at }}</small></a>
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
