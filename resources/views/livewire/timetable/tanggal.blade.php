<div>
    <div class="d-flex mb-2 justify-content-md-evenly" style="overflow-x: auto">
        @foreach ($listTanggal as $index => $item)
        <a href="#{{ $index }}"
            wire:click="$emit('onClickTanggal', {{ $index }}, {{ json_encode(date('Y-m-d', strtotime($item))) }})"
            class="card-date @if ($index == $indexTanggal) active @endif shadow-sm mx-1" style="text-decoration: none;">
            <div class="text-center">
                <small class="fw-light">{{ date('M', strtotime($item)) }}</small>
            </div>
            <div class="fw-bold text-center fs-4">{{ date('d', strtotime($item)) }}</div>
        </a>
        @endforeach
    </div>
    <div class="row d-none d-sm-flex justify-content-center align-items-center">
        <div class="dropdown col-md-3">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Classes
            </button>
            <ul class="w-100 dropdown-menu px-2">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeClass',0 )">Close Class</a>
                </li>
                @foreach ($listKelas as $item)
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeClass', {{ $item->id }})">{{
                        $item->nama_kelas }}</a>
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
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeInstruktur', 0)">All Instruktur</a>
                </li>
                @foreach ($listInstruktur as $item)
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeInstruktur', {{ $item->id }})">{{
                        $item->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input type="date" class="form-control" name="tanggal" wire:model='tanggal' placeholder="Cari Kelas ..." wire:change='changeTanggal2'
                    aria-label="Tulis Kelas" aria-describedby="basic-addon2"
                    style="padding-top: 10px; padding-bottom: 10px">
            </div>
        </div>
        <div class="col-md-3">
            <button type="button" id="btn_repetitif_time" wire:click="$emit('onClickGantiLokasi')"
                class="btn custom-btn w-100">Ganti Lokasi</button>
        </div>
    </div>
    <div class="row d-sm-none my-3 align-items-center justify-content-center">
        <div class="dropdown col-10">
            <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                {{ $nama_kelas }}
            </button>
            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeClass',0 )">Close Class</a></li>
                @foreach ($listKelas as $item)
                <li><a class="dropdown-item" href="#" wire:click="$emit('onChangeClass', {{ $item->id }})">{{
                        $item->nama_kelas }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-2">
            <a href="#" id="btn-filter">
                <i class="fa-solid fa-sliders fs-4 text-dark"></i>
            </a>
        </div>
    </div>
</div>

@push('js')
<script>
    Livewire.on('onClickTanggal', (index, tanggal) => {
            Livewire.emit('setTanggal', index)
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

        Livewire.on('onClickBtnRepetitifTime', () => {
            $('#modal_repetitif_time').modal('show')
        })

        Livewire.on('onClickGantiLokasi', () => {
            $('#modal_location_studio').modal('show')
        })
</script>
@endpush