<div>
    <div class="d-flex mb-2 justify-content-evenly" style="overflow-x: auto">
        @foreach ($listTanggal as $index => $item)
            <a href="#{{ $index }}" wire:click="$emit('onClickTanggal', {{ $index }})"
                class="card-date @if ($index == $indexTanggal) active @endif shadow-sm mx-1"
                style="text-decoration: none;">
                <div class="text-center">
                    <small class="fw-light">{{ date('M', strtotime($item)) }}</small>
                </div>
                <div class="fw-bold text-center fs-4">{{ date('d', strtotime($item)) }}</div>
            </a>
        @endforeach
    </div>
    <div class="row d-none d-sm-flex justify-content-center align-items-center">
        {{-- <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                All Categories
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeCategory', 0)">All Categories</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeCategory', 1)">Private Class</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeCategory', 2)">Group Class</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeCategory', 3)">Private Duet</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeCategory', 4)">Private Group</a>
                </li>
            </ul>
        </div> --}}
        {{-- <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Location
            </button>
            <ul class="w-100 dropdown-menu px-2">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeLocation',0 )">All Location</a>
                </li>
                @foreach ($listLocation as $item)
                    <li><a class="dropdown-item" href="#"
                            wire:click="$emit('onChangeLocation', {{ $item->id }})">{{ $item->nama }}</a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
        {{-- <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" name="cari" wire:model='cari' placeholder="Cari Kelas ..."
                    aria-label="Tulis Kelas" aria-describedby="basic-addon2"
                    style="padding-top: 10px; padding-bottom: 10px">
                <span wire:click="onClickCari" class="input-group-text" id="basic-addon2" style="cursor: pointer">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </span>
            </div>
        </div> --}}

        <div class="dropdown col-md-3">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Classes
            </button>
            <ul class="w-100 dropdown-menu px-2">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeClass',0 )">Close Class</a>
                </li>
                @foreach ($listKelas as $item)
                    <li><a class="dropdown-item" href="#"
                            wire:click="$emit('onChangeClass', {{ $item->id }})">{{ $item->nama_kelas }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Instructor
            </button>
            <ul class="w-100 dropdown-menu px-2">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeInstruktur', 0)">All
                        Instruktur</a>
                </li>
                @foreach ($listInstruktur as $item)
                    <li><a class="dropdown-item" href="#"
                            wire:click="$emit('onChangeInstruktur', {{ $item->id }})">{{ $item->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        {{-- <div class="dropdown col-md-3">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Level of difficulty
            </button>
            <ul class="w-100 dropdown-menu px-2">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeLevel', 0)">All Level</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeLevel', 1)">Intermediated</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeLevel', 2)">Beginer</a>
                </li>
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeLevel', 3)">Advanced</a>
                </li>
            </ul>
        </div> --}}
        <div class="col-md-3">
            <div class="input-group">
                <input type="date" class="form-control" name="tanggal" wire:model='tanggal'
                    wire:change='changeTanggal' placeholder="Cari Kelas ..." aria-label="Tulis Kelas"
                    aria-describedby="basic-addon2" style="padding-top: 10px; padding-bottom: 10px">
            </div>
        </div>
    </div>
    <div class="row d-sm-none my-3 align-items-center justify-content-center">
        <div class="dropdown col-10">
            <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Class
            </button>
            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeClass',0 )">Close Class</a></li>
                @foreach ($listKelas as $item)
                    <li><a class="dropdown-item" href="#"
                            wire:click="$emit('onChangeClass', {{ $item->id }})">{{ $item->nama_kelas }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-2">
            <a href="#" id="btn-filter">
                <i class="fa-solid fa-sliders fs-4 text-dark"></i>
            </a>
        </div>
    </div>
    <div class="text-center">
        <div class="mt-3" wire:loading wire:target='changeClass'>
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('onClickTanggal', (tanggal) => {
            Livewire.emit('setTanggal', tanggal)
        })

        Livewire.on('onChangeCategory', (category) => {
            Livewire.emit('changeCategory', category);
        })

        Livewire.on('onChangeLocation', (id_location) => {
            Livewire.emit('changeLocation', id_location);
        })

        Livewire.on('onChangeClass', (id_kelas) => {
            Livewire.emit('changeClass', id_kelas);
        })

        Livewire.on('onChangeInstruktur', (id_user) => {
            Livewire.emit('changeInstruktur', id_user);
        })

        Livewire.on('onChangeLevel', (level) => {
            Livewire.emit('changeLevel', level);
        })
    </script>
@endpush
