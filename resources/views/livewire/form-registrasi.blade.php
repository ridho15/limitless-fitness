<div class="bg-white shadow min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="w-100 px-md-5 px-3">
        <div class="text-center mb-3">
            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid" style="object-fit: cover">
        </div>
        <div class="text-center fw-bold mb-5">
            Enter Your Data Account
        </div>
        <form method="POST" action="{{ route('postRegistrasi') }}">
            @csrf
            @if (session()->has('fail'))
                <div class="text-center">
                    <div class="alert alert-danger text-danger" role="alert">
                        {{ session('fail') }}
                    </div>
                </div>
            @elseif(session()->has('success'))
                <div class="text-center">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            <label for="" class="form-label">Nama</label>
            <div class="input-group mb-3">
                <input type="text" name="name" wire:model="nama" class="form-control padding-vertical-12"
                    placeholder="Masukkan nama" aria-label="Masukkan nama" value="{{ session()->get('name') }}"
                    aria-describedby="basic-addon2">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-file-signature px-2"></i></span>
            </div>
            <label for="" class="form-label">Email</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control padding-vertical-12" wire:model="email" name="email"
                    value="{{ session()->get('email') }}" placeholder="Masukkan email" aria-label="Masukkan email"
                    aria-describedby="basic-addon2">
                <span class="input-group-text" id="basic-addon2"><i class="fa-regular fa-envelope px-2"></i></span>
            </div>
            <label for="" class="form-label">Nomor Hp</label>
            <div class="input-group mb-3">
                <input type="number" class="form-control padding-vertical-12" name="no_hp" wire:model="no_hp"
                    value="{{ session()->get('no_hp') }}" placeholder="Phone number" aria-label="Phone number"
                    aria-describedby="basic-addon2">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-phone px-2"></i></span>
            </div>
            <label for="" class="form-label">Location / Kota</label>
            <div class="input-group mb-3">
                <select name="id_location" class="form-control" wire:model="id_location"
                    data-placeholder="Pilih Location" required>
                    <option value="">Pilih</option>
                    @foreach ($listLocation as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <div class="mb-3 input-group">
                <input type="password" class="form-control padding-vertical-12" wire:model="password" name="password"
                    value="{{ session()->get('password') }}" placeholder="Masukkan password" id="exampleInputPassword1">
                <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-key px-2"></i></span>
            </div>
            <label for="exampleInputPassword1" class="form-label">Alamat</label>
            <div class="mb-3 input-group">
                <textarea name="alamat" class="form-control padding-vertical-12" wire:model="alamat" placeholder="Masukkan alamat">{{ session()->get('alamat') }}</textarea>
                <span class="input-group-text" id="basic-addon2"><i
                        class="fa-solid fa-map-location-dot px-2"></i></span>
            </div>
            <div class="text-center">
                <button type="submit" class="btn custom-btn w-100">Submit</button>
            </div>
            <div class="row justify-content-center align-items-center my-4">
                <div class="col-md-5 col-5">
                    <hr>
                </div>
                <div class="col-md-1 col-1 text-center">
                    Or
                </div>
                <div class="col-md-5 col-5">
                    <hr>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('login') }}" class="btn custom-btn-outline w-100">Login</a>
            </div>
        </form>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            select2()
        });

        window.addEventListener('contentChange', function() {
            select2()
        })

        function select2() {
            $('select[name="id_location"]').select2({
                theme: 'bootstrap-5'
            });

            $('select[name="id_location_studio"]').select2({
                theme: 'bootstrap-5'
            });

            $('select[name="id_location"]').on('change', function() {
                Livewire.emit('changeLocation', $(this).val())
            })

            $('select[name="id_location_studio"]').on('change', function() {
                Livewire.emit('changeLocationStudio', $(this).val())
            })
        }
    </script>
@endpush
