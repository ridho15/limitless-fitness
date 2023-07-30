<div>
    <div class="d-flex mb-2" style="overflow-x: scroll">
        @foreach ($listTanggal as $index => $item)
            <a href="#{{ $index }}" wire:click="$emit('onClickTanggal', {{ $index }})" class="card-date @if($index == $indexTanggal) active @endif shadow-sm mx-1" style="text-decoration: none;">
                <div class="text-center">
                    <small class="fw-light">{{ date('M', strtotime($item)) }}</small>
                </div>
                <div class="fw-bold text-center fs-4">{{ date('d', strtotime($item)) }}</div>
            </a>
        @endforeach
    </div>
    <div class="row d-none d-sm-flex justify-content-center">
        <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                All Categories
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All Categories</a></li>
                <li><a class="dropdown-item" href="#">Group Classes</a></li>
                <li><a class="dropdown-item" href="#">Private Classes</a></li>
            </ul>
        </div>
        <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Location
            </button>
            <div class="w-100 dropdown-menu px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
            </div>
        </div>
        <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Classes
            </button>
            <div class="w-100 dropdown-menu px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
            </div>
        </div>
        <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Instructor
            </button>
            <div class="w-100 dropdown-menu px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
            </div>
        </div>
        <div class="dropdown col-md-2">
            <button class="btn btn-custom-filter dropdown-toggle shadow-sm border w-100" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Level of difficulty
            </button>
            <div class="w-100 dropdown-menu px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-sm-none my-3 align-items-center justify-content-center">
        <div class="dropdown col-10">
            <button class="btn btn-custom-filter btn-block dropdown-toggle shadow-sm border w-100"
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                All Class Types
            </button>
            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item" href="#">All Class Type</a></li>
                <li><a class="dropdown-item" href="#">Online</a></li>
                <li><a class="dropdown-item" href="#">Onsite</a></li>
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
        Livewire.on('onClickTanggal', (tanggal) => {
            Livewire.emit('setTanggal', tanggal)
        })
    </script>
@endpush
