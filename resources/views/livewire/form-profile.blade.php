<div>
    <form action="{{ route('updateProfile') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" id="name" wire:model="nama"
                    style="border-radius: 10px" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" wire:model="email"
                    aria-describedby="emailHelp" style="border-radius: 10px" required>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="number" name="no_hp" class="form-control" id="mobile_number" wire:model="no_hp"
                    style="border-radius: 10px" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" style="border-radius: 10px">
            </div>
            <div class="col-md-6 mb-3">
                <label for="id_location" class="form-label">Location</label>
                <select name="id_location" class="form-control" wire:model="id_location" data-placeholder="Pilih"
                    required>
                    <option value="">Pilih</option>
                    @foreach ($listLocation as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" wire:model="tanggal_lahir"
                    id="tanggal_lahir" style="border-radius: 10px">
            </div>
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Jenis Kelamin</label>
                <div class="d-flex align-items-center justify-content-evenly">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="jenis_kelamin" name="jenis_kelamin"
                            value="1" id="pria">
                        <label class="form-check-label" for="pria">
                            Pria
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="jenis_kelamin" name="jenis_kelamin"
                            value="0" id="wanita">
                        <label class="form-check-label" for="wanita">
                            Wanita
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="negara">Negara</label>
                <input type="text" name="negara" class="form-control" wire:model="negara" id="negara" required
                    style="border-radius: 10px">
            </div>
            <div class="col-md-6 mb-3">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" class="form-control" wire:model="alamat" id="alamat" style="border-radius: 10px"></textarea>
            </div>
            <div class="mb-3 col-md-6">
                <div class="mt-2" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false, progress = 0"
                    x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <!-- File Input -->
                    <label for="" class="form-label required">Foto KTP</label>
                    <input type="file" name="ktp" wire:model="ktp" class="form-control padding-vertical-12"
                        placeholder="Upload KTP" required>
                    <div><small>Wajib Upload KTP Max File 1MB</small></div>

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
            <div class="mb-3 col-md-6">
                <div class="mt-2" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false, progress = 0"
                    x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <!-- File Input -->
                    <label for="" class="form-label required">Foto Selfie</label>
                    <input type="file" name="selfie" wire:model="selfie"
                        class="form-control padding-vertical-12" placeholder="Upload Selfie" required>
                    <div><small>Wajib Upload Foto Selfie Max File 1MB</small></div>
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
        </div>
        <div class="text-end">
            <button type="submit" class="btn custom-btn"><i class="fa-solid fa-floppy-disk"></i>
                Simpan</button>
        </div>
    </form>
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
            })

            $('select[name="id_studio"]').select2({
                theme: 'bootstrap-5'
            })

            $('select[name="id_location"]').on('change', function() {
                Livewire.emit('changeLocation', $(this).val())
            })

            $('select[name="id_studio"]').on('change', function() {
                Livewire.emit('changeLocationStudio', $(this).val())
            })
        }
    </script>
@endpush
