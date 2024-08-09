<div class="bg-white shadow min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="w-100 px-md-5 px-3">
        <div class="text-center mb-3">
            <img src="{{ asset('vertical/design_7_black.png') }}" alt="" class="img-fluid"
                style="object-fit: contain; height: 300px;">
        </div>
        <div class="text-center fw-bold mb-5">
            Enter Your Data Account
        </div>
        <form method="POST" action="#" wire:submit.prevent='postRegistration'>
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
            <div class="mb-3">
                <label for="" class="form-label">Nama</label>
                <div class="input-group">
                    <input type="text" name="name" wire:model="name" class="form-control padding-vertical-12"
                        placeholder="Masukkan nama" aria-label="Masukkan nama" aria-describedby="basic-addon2" required>
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-file-signature px-2"></i></span>
                </div>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">NIK</label>
                <div class="input-group">
                    <input type="number" name="nik" wire:model="nik" class="form-control padding-vertical-12"
                        placeholder="Masukkan NIK" aria-label="Masukkan NIK" aria-describedby="basic-addon2" required>
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-address-card px-2"></i></span>
                </div>
                @error('nik')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Email</label>
                <div class="input-group">
                    <input type="text" class="form-control padding-vertical-12" wire:model="email" name="email"
                        placeholder="Masukkan email" aria-label="Masukkan email" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2"><i class="fa-regular fa-envelope px-2"></i></span>
                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Nomor Hp</label>
                <div class="input-group">
                    <input type="number" class="form-control padding-vertical-12" name="no_hp" wire:model="no_hp"
                        value="{{ session()->get('no_hp') }}" placeholder="Phone number" aria-label="Phone number"
                        aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-phone px-2"></i></span>
                </div>
                @error('no_hp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Location / Kota</label>
                <div class="input-group">
                    <select name="id_location" class="form-control" wire:model="id_location"
                        data-placeholder="Pilih Location" required>
                        <option value="">Pilih</option>
                        @foreach ($listLocation as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @error('id_location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control padding-vertical-12" wire:model="password"
                        name="password" value="{{ session()->get('password') }}" placeholder="Masukkan password"
                        id="exampleInputPassword1">
                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-key px-2"></i></span>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Alamat</label>
                <div class="input-group">
                    <textarea name="alamat" class="form-control padding-vertical-12" wire:model="alamat" placeholder="Masukkan alamat">{{ session()->get('alamat') }}</textarea>
                    <span class="input-group-text" id="basic-addon2"><i
                            class="fa-solid fa-map-location-dot px-2"></i></span>
                </div>
                @error('alamat')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Jenis Kelamin</label>
                <div class="d-flex align-items-center justify-content-evenly">
                    <div class="form-check">
                        <input class="form-check-input" wire:model="jenis_kelamin" value="1" type="radio"
                            name="jenis_kelamin" id="jenis_kelamin_1" required>
                        <label class="form-check-label" for="jenis_kelamin_1">
                            Laki-Laki
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" value="2" wire:model="jenis_kelamin" type="radio"
                            name="jenis_kelamin" id="jenis_kelamin_2" required>
                        <label class="form-check-label" for="jenis_kelamin_2">
                            Perempuan
                        </label>
                    </div>
                </div>
                @error('jenis_kelamin')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <div class="mt-2" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false, progress = 0"
                    x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <!-- File Input -->
                    <label for="" class="form-label required">Foto KTP</label>
                    <input type="file" name="ktp" wire:model="ktp" class="form-control padding-vertical-12"
                        placeholder="Upload KTP" required>
                    <div><small>Wajib Upload KTP Max File 2MB</small></div>

                    <!-- Progress Bar -->
                    <div x-show="isUploading" class="progress" style="height: 10px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                            x-bind:style="`width: ${progress}%`"></div>
                    </div>
                </div>
                @error('ktp')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                @if ($ktp != null)
                    <div class="text-center border border-light rounded">
                        <img src="{{ $ktp->temporaryUrl() }}" alt=""
                            style="object-fit: contain; width: 100%;">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <div class="mt-2" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false, progress = 0"
                    x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <!-- File Input -->
                    <label for="" class="form-label required">Foto Selfie</label>
                    <input type="file" name="selfie" wire:model="selfie"
                        class="form-control padding-vertical-12" placeholder="Upload Selfie" required>
                    <div><small>Wajib Upload Foto Selfie Max File 2MB</small></div>
                    <!-- Progress Bar -->
                    <div x-show="isUploading" class="progress" style="height: 10px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                            x-bind:style="`width: ${progress}%`"></div>
                    </div>
                </div>
                @error('selfie')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                @if ($selfie != null)
                    <div class="text-center border border-light rounded">
                        <img src="{{ $selfie->temporaryUrl() }}" alt=""
                            style="object-fit: contain; width: 100%;">
                    </div>
                @endif
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="flexCheckChecked" wire:model="setuju" required>
                <label class="form-check-label text-sm" for="flexCheckChecked">
                    <small>Apakah kamu menyetujui untuk memberikan data KTP dan Foto Selfie ?</small>
                </label>
            </div>
            <div class="text-center my-3">
                <div wire:loading wire:target="postRegistration">
                    <i class="fa-solid fa-sync fa-spin fa-xl"></i>
                </div>
            </div>
            <div class="text-center mb-3">
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

        Livewire.on('finishSubmit', (status, message) => {
            alert(status, message);
        })
    </script>
@endpush
