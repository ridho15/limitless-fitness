@extends('base-2.layout')

@section('content')
    {{-- Start Slider --}}
    <section>
        @livewire('caraousel')
    </section>
    {{-- End Slider --}}
    {{-- List Class --}}
    <section class="py-5">
        @livewire('home.kelas')
    </section>

    <section class="my-5">
        <div class="container">
            <h3 class="fw-bold mb-4 text-center">Near The Heart of Jakarta</h3>
            <p class="fw-light text-center mb-5">We are located in a strategic part of Jakarta</p>
            <div class="text-center my-5">
                <button class="btn custom-btn fw-light"
                    style="padding-top: 15px; padding-bottom: 15px; border-radius: 30px">Let's go there</button>
            </div>
            <div class="w-100 position-relative d-flex align-items-center justify-content-center custom-card"
                style="background-image: url('https://i.etsystatic.com/11143919/r/il/a74af7/2577032224/il_fullxfull.2577032224_3sk0.jpg');">
                <div class="h-100 position-absolute bg-gradient w-100 opacity-card-black"></div>
                <div class="position-absolute d-flex align-items-end justify-content-center h-100 w-100 card-class-text"
                    style="z-index: 10;">
                    <div class="px-4 py-4">
                        <h2 class="text-white fw-bold mb-4">
                            Bumi Studio Jakarta
                        </h2>
                        <small class="d-block fw-light text-white mb-2">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="ms-2">
                                Jl. Bumi No. 4, Kebayoran Baru, Jakarta Selatan, 12120,Second Floor,Daerah Khusus Ibukota
                                Jakarta,12120,Kota Jakarta Selatan
                            </span>
                        </small>
                        <small class="d-block fw-light text-white mb-4">
                            <i class="fa-solid fa-phone"></i> <span class="ms-2">
                                +62 819 4450 2222
                            </span>
                        </small>
                        <button class="btn btn-outline-dark shadow-sm custom-btn-white">View More</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End List Class --}}
@endsection
