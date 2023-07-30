<div class="container">
    <h3 class="fw-bold text-center">
        Signature Class Type
    </h3>
    <p class="text-center">All our classes are aimed to help you to progress in your Pilates journey</p>
    <div class="d-flex align-items-center my-5 justify-content-center">
        <a href="{{ route('class') }}" class="btn btn-outline-dark custom-btn custom-btn-outline me-5">
            See All Classes
        </a>
        <a href="{{ route('timetable') }}" class="btn btn-dark ms-5 custom-btn">
            Book A Class
        </a>
    </div>
    <div class="row my-5">
        @foreach ($listKelas as $item)
            <div class="col-md-4 mb-4">
                <div class="custom-card position-relative"
                    style="background-image: url({{ count($item->kelasImage) > 0 ? asset('storage/' . $item->kelasImage[0]->image) : null }})">
                    <div class="h-100 position-absolute bg-gradient w-100 opacity-card-black"></div>
                    <div class="position-absolute d-flex align-items-end justify-content-center h-100 w-100 card-class-text"
                        style="z-index: 10;">
                        <div class="px-4 py-4">
                            <h2 class="text-white fw-bold">
                                {{ $item->nama_kelas }}
                            </h2>
                            <div class="d-flex mb-3">
                                <small class="text-white fw-light justify-content-center align-items-center">
                                    @if ($item->rating != 0)
                                        @for ($i = 1; $i <= $item->rating; $i++)
                                            <i class="fa-solid fa-star text-warning"></i>
                                        @endfor
                                        @for ($i = 1; $i <= 5 - $item->rating; $i++)
                                            <i class="fa-regular fa-star text-warning"></i>
                                        @endfor
                                        <span class="ms-2"
                                            style="padding-right: 15px; padding-left: 15px; border: 1px solid white; border-radius: 15px;">{{ $item->kelasRating->count() }}
                                            Review</span>
                                    @else
                                        <i class="fa-solid fa-star text-warning"></i> No
                                        Rating Yet
                                    @endif
                                </small>
                            </div>
                            <p class="fw-light text-white">
                                {{-- Bring a friend - two is better than one! This semi-private class is perfect for those
                                who don't like working out alone. ... --}}
                                {{ substr($item->keterangan, 0, 100) }} ...
                            </p>
                            <a href="{{ route('detail-class', ['id' => 1]) }}"
                                class="btn btn-outline-dark shadow-sm custom-btn-white">View More</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
