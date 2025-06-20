@extends('base-2.layout')

@section('content')
<section class="my-4">
    <div class="container mb-3">
        <div class="d-flex mb-3">
            <small class="fw-light mx-1">Classes</small>
            <small class="mx-1"><i class="fa-solid fa-chevron-right"></i></small>
            <small class="fw-bold mx-1">{{ $kelas->nama_kelas }}</small>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="fw-bold mb-2">{{ $kelas->nama_kelas }}</h3>
            <div class="text-center">
                <a href="#" type="submit" class="btn custom-btn w-100">Book Now</a>
            </div>
        </div>
        <div class="row mb-3 justify-content-start align-items-center">
            <div class="col-md-4 col-6 text-start">
                <small class="d-block fw-semibold"><i class="fa-solid fa-clock-rotate-left"></i>
                    Jadwal </small>
                <small class="d-block fw-light">{{ Date('H:i', strtotime($kelas->waktu_buka)) }} - {{
                    Date("H:i", strtotime($kelas->waktu_tutup)) }}</small>
            </div>
        </div>
        <div class="row justify-content-start align-items-center">
            <div class="col-md-4 col-6 text-start">
                <small class="d-block fw-semibold"><i class="fa-solid fa-location-dot"></i>
                    Location </small>
                <small class="d-block fw-light">{{ $kelas->location->nama }}</small>
            </div>
        </div>
        <div class="card" style="border: none; background-color: white">
            <div class="card-body">
                <div class="text-center">
                    <img src="https://tribratanews.polri.go.id/web/image/blog.post/64327/image"
                        class="img-fluid w-100 mb-3" alt="" style="border-radius: 10px">
                </div>
                <h4>{{ $kelas->nama_kelas }} @if (count($kelas->instruktur) > 0)
                    (@foreach ($kelas->instruktur as $index => $item)
                    {{ $item->name }} @if (count($kelas->instruktur) > 1)
                    ,
                    @endif
                    @endforeach)
                    @endif</h4>
                <p class="text-justify">
                    <small>{{ $kelas->keterangan }}</small>
                </p>
            </div>
        </div>
        <h5 class="fw-bold mb-3">Other Class</h5>
        <div class="row">
            @if (count($listKelas) > 0)
            @foreach ($listKelas as $index => $item)
            <div class="col-lg-4 col-md-6 mb-5">
                <div class="card h-100"
                    style="border-color: rgb(239, 239, 239); border-radius: 10px; background-color: rgb(239, 239, 239)">
                    <a class="h-100" href="{{ route('kelas.detail', ['id' => $item->id]) }}"
                        style="text-decoration: none; color: black">
                        <div class="card-body h-100">
                            <div class="d-flex h-100 flex-column align-items-center justify-content-between">
                                <div class="text-center">
                                    @if (count($item->kelasImage) > 0)
                                    <img src="{{ asset('storage/' . $item->kelasImage->first()->image) }}"
                                        class="img-fluid rounded mb-3" alt="" style="border-radius: 10px">
                                    @else
                                    <img src="{{ asset('assets/images/default-image.jpg') }}" alt=""
                                        class="img-fluid rounded mb-3" style="border-radius: 10px">
                                    @endif
                                </div>
                                <h5 class="fw-bold w-100 text-start">{{ $item->nama_kelas }} @if(count($item->instruktur) > 0)
                                    (@foreach ($item->instruktur as $index => $instruktur)
                                    {{ $instruktur->name }} @if (count($item->instruktur) > 1)
                                    ,
                                    @endif
                                    @endforeach)
                                    @endif</h5>
                                <div class="mb-3 w-100 text-start" style="flex: 1">
                                    <p class="max-lines">
                                        <smal>{{ $item->keterangan }}</small>
                                    </p>
                                </div>
                                <div class="w-100 d-flex align-items-center justify-content-between">
                                    <div class="">
                                        {{ $item->location->nama }}
                                    </div>
                                    <div class="text-end">
                                        <span>{{ Date('H:i', strtotime($item->waktu_buka)) }} - {{ Date("H:i",
                                            strtotime($item->waktu_tutup)) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3 w-100">
                                    <span class="badge rounded-pill bg-dark">
                                        Available / 1 slot remaining
                                    </span>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('kelas.detail', ['id' => $item->id]) }}"
                                        class="btn custom-btn w-100">View More</a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
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