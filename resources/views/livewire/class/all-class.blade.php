<div class="row mt-5 mb-5">
    <div class="text-center">
        <div wire:loading class="text-center">
            Loading ...
        </div>
    </div>
    @if (count($listKelas) > 0)
    @foreach ($listKelas as $item)
    <div class="col-lg-4 col-md-6 mb-5">
        <div class="card h-100"
            style="border-color: rgb(239, 239, 239); border-radius: 10px; background-color: rgb(239, 239, 239)">
            <div class="card-body h-100">
                <div class="d-flex h-100 flex-column align-items-center justify-content-between">
                    <div class="text-center">
                        @if (count($item->kelasImage) > 0)
                        <img src="{{ storage_url($item->kelasImage->first()->image) }}"
                            class="img-fluid rounded mb-3" alt="" style="border-radius: 10px">
                        @else
                        <img src="{{ asset('assets/images/default-image.jpg') }}" alt="" class="img-fluid rounded mb-3"
                            style="border-radius: 10px">
                        @endif
                    </div>
                    <h5 class="fw-bold w-100 text-start">{{ $item->nama_kelas }} @if (count($item->instruktur) > 0)
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
                        <a href="{{ route('kelas.detail', ['id' => $item->id]) }}" class="btn custom-btn w-100">View
                            More</a>
                    </div>
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
@push('js')
<script>
    window.addEventListener('contentChange', function(){
    })

    $(document).ready(function () {
    });
</script>
@endpush