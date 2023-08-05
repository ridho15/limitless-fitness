<div class="row mt-5 mb-5">
    @if (count($listKelas) > 0)
        @foreach ($listKelas as $item)
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="custom-card-classes">
                    <div class="w-100 bg-dark image-custom-card"
                        style="background-image: url({{ count($item->kelasImage) > 0 ? asset('storage/' . $item->kelasImage[0]->image) : null }});">
                    </div>
                    <div class="px-4 py-4">
                        <h5 class="fw-bold">{{ $item->nama_kelas }}</h5>
                        {{-- <div class="d-flex justify-content-start align-items-center my-3">
                            <small class="me-2">
                                @for ($i = 1; $i <= $item->rating; $i++)
                                    <i class="fa-solid fa-star text-warning"></i>
                                @endfor
                                @for ($i = 1; $i <= 5 - $item->rating; $i++)
                                    <i class="fa-regular fa-star text-warning"></i>
                                @endfor
                            </small>
                            <small class="ms-2 px-2 py-2" style="border: 1px solid grey; border-radius: 20px">
                                {{ $item->jumlah_review }}
                                Review</small>
                        </div> --}}
                        <div class="d-flex align-items-center justify-content-start my-2">
                            <div class="me-2">
                                <small class="fw-bold">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    {{ $item->duration }} Mins</small>
                            </div>
                            <div class="ms-2">
                                <small class="fw-bold"><i
                                        class="fa-brands fa-creative-commons-nd"></i>{{ $item->level_formatted }}</small>
                            </div>
                        </div>
                        <div class="my-2">
                            <p>{{ substr($item->keterangan, 0, 150) }}...</p>
                        </div>
                        <div>
                            <a href="{{ route('detail-class', ['id' => $item->id]) }}" class="btn custom-btn">View
                                More</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12 text-center fw-bold">
            Tidak ada kelas
        </div>
    @endif
</div>
