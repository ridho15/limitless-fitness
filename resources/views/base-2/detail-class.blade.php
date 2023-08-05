@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-8 mb-3">
                    <div class="d-flex mb-3">
                        <small class="fw-light mx-1">Classes</small>
                        <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
                        <small class="fw-bold mx-1">{{ $kelas->nama_kelas }}</small>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $kelas->nama_kelas }}</h3>

                    {{-- <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @for ($i = 1; $i <= $kelas->rating; $i++)
                                <i class="fa-solid fa-star text-warning"></i>
                            @endfor
                            @for ($i = 1; $i <= 5 - $kelas->rating; $i++)
                                <i class="fa-regular fa-star text-warning"></i>
                            @endfor
                        </div>
                        <span class="px-3 py-1" style="border: 1px solid grey; border-radius: 15px">
                            <small class="fw-light">{{ $jumlahReview }} Review</small>
                        </span>
                    </div> --}}
                    <div class="row mb-4 justify-content-start align-items-center">
                        <div class="col-md-4 col-6 text-start">
                            <small class="d-block fw-semibold"><i class="fa-solid fa-clock-rotate-left"></i>
                                Duration</small>
                            <small class="d-block fw-light">{{ $kelas->duration }} mins</small>
                        </div>
                        {{-- <div class="col-md-4 col-6 text-center">
                            <small class="d-block fw-semibold"><i class="fa-solid fa-timeline"></i> Difficulty Level</small>
                            <small class="d-block fw-light">Untuk {{ $kelas->level_formatted }}</small>
                        </div> --}}
                    </div>
                    <div class="shadow bg-white p-4" style="border-radius: 15px">
                        <form action="#" method="GET" id="form_book">
                            <div class="row align-items-center">
                                <div class="col-md mb-3">
                                    <select name="location" class="form-control" data-control="select2"
                                        data-placeholder="Pilih" id="location">
                                        <option value="">Pilih</option>
                                        @foreach ($listPaket as $item)
                                            <option value="{{ $item->paket->id }}">{{ $item->paket->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <button type="submit" class="btn custom-btn w-100">
                                        <small class="fw-light">Book Now</small>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ count($kelas->kelasImage) > 0 ? asset('storage/' . $kelas->kelasImage[0]->image) : null }}"
                        class="img-fluid rounded shadow-sm" alt="">
                </div>
            </div>
            <hr class="my-5">
            <div class="row">
                <div class="col-md-6 fw-bold fs-4">
                    About the class
                </div>
                <div class="col-md-6">
                    <small class="fw-light">
                        {{ $kelas->keterangan }}
                    </small>
                </div>
            </div>
            <hr class="my-5">
            <div class="row">
                <div class="col-md-6 fw-bold fs-4">
                    What do you need to bring?
                </div>
                <div class="col-md-6">
                    <small class="fw-light">
                        @php
                            $listBawaan = explode(',', $kelas->bawaan);
                        @endphp

                        <ul>
                            @foreach ($listBawaan as $item)
                                @if ($item != null)
                                    <li>{{ $item }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </small>
                </div>
            </div>
            <hr class="my-5">
            {{-- <div class="row mb-5">
                <div class="col-md-6 fw-bold fs-4">
                    Reviews
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-bold fs-4 me-2">{{ $kelas->rating }}</span>
                        <small class="fw-light">{{ $jumlahReview }} reviews</small>
                    </div>
                    <div class="d-flex align-items-center mb-3 me-3">
                        @for ($i = 1; $i <= $kelas->rating; $i++)
                            <i class="fa-solid fa-star text-warning me-1"></i>
                        @endfor
                        @for ($i = 1; $i <= 5 - $kelas->rating; $i++)
                            <i class="fa-regular fa-star text-warning me-1"></i>
                        @endfor
                    </div>
                    <hr class="my-4">
                    @foreach ($kelas->kelasActivity as $index => $kelasActivity)
                        @foreach ($kelasActivity->kelasRating as $item)
                            <div class="mb-3">
                                <strong>{{ $kelas->nama_kelas }}</strong> <small class="fw-light">with
                                    {{ $kelasActivity->user->name }}</small>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <small class="me-2">
                                    @for ($i = 1; $i <= $kelas->rating; $i++)
                                        <i class="fa-solid fa-star text-warning"></i>
                                    @endfor
                                    @for ($i = 1; $i <= 5 - $kelas->rating; $i++)
                                        <i class="fa-regular fa-star text-warning"></i>
                                    @endfor

                                </small>
                                <small class="fw-light"><i
                                        class="fa-solid fa-house-chimney-user me-2"></i>{{ $kelasActivity->locationStudio->nama }}
                                    {{ $kelasActivity->locationStudio->location->nama }}</small>
                            </div>
                            <div class="mb-2">
                                <small class="fw-light">{{ $item->comment }}</small>
                            </div>
                            <div class="mb-2">
                                <small class="fw-light text-secondary">Posted {{ $item->waktu_lalu }}</small>
                            </div>
                            <hr class="my-4">
                        @endforeach
                    @endforeach
                </div>
            </div>
            <div class="text-center mb-5">
                <button class="btn custom-btn w-100">
                    <small>See all review</small>
                </button>
            </div> --}}
        </div>
        {{-- <div class="container my-5">
            <hr>
        </div>
        <div class="container">
            <div class="row my-4">
                <div class="col-md-12">
                    <h3 class="fw-bold">Related Classes</h3>
                </div>
            </div>
            <div class="row my-5">
                @if (count($relatedClass) > 0)
                    @foreach ($relatedClass as $item)
                        <div class="col-lg-4 col-md-6 mb-5">
                            <div class="custom-card-classes">
                                <div class="w-100 bg-dark image-custom-card"
                                    style="background-image: url({{ count($item->kelasImage) > 0 ? asset('storage/' . $item->kelasImage[0]->image) : null }});">
                                </div>
                                <div class="px-4 py-4">
                                    <h5 class="fw-bold">{{ $item->nama_kelas }}</h5>
                                    <div class="d-flex justify-content-start align-items-center my-3">
                                        <small class="me-2">
                                            @for ($i = 1; $i <= $item->rating; $i++)
                                                <i class="fa-solid fa-star text-warning"></i>
                                            @endfor
                                            @for ($i = 1; $i <= 5 - $item->rating; $i++)
                                                <i class="fa-regular fa-star text-warning"></i>
                                            @endfor
                                        </small>
                                        <small class="ms-2 px-2 py-2"
                                            style="border: 1px solid grey; border-radius: 20px">{{ $item->jumlah_review }}
                                            Review</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start my-2">
                                        <div class="me-2">
                                            <small class="fw-bold">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                                {{ $item->duration }} Mins</small>
                                        </div>
                                        <div class="ms-2">
                                            <small class="fw-bold"><i class="fa-brands fa-creative-commons-nd"></i>Open to
                                                {{ $item->level_formatted }}</small>
                                        </div>
                                    </div>
                                    <div class="my-2">
                                        <p>{{ substr($item->keterangan, 0, 150) }}</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('detail-class', ['id' => $item->id]) }}"
                                            class="btn custom-btn">View More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12 py-5 text-center fw-light">
                        Tidak ada kelas
                    </div>
                @endif
            </div>
        </div> --}}
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#location').select2({
                theme: 'bootstrap-5'
            });
        });

        $('#location').on('change', function() {
            const id = $(this).val()
            $('#form_book').attr('action', "{{ config('app.url') }}/detail-packages/" + id);
        })
    </script>
@endsection
