<div wire:ignore.self class="modal fade" id="modal_review_kelas" tabindex="-1" aria-labelledby="modal_tambah_kredit"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #faf0e6">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal_review_kelas">Review Kelas Latihan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" wire:submit.prevent="simpanReview" method="POST">
                <div class="modal-body">
                    @include('helper.alert-message')
                    <div class="d-flex justify-content-evenly align-items-center">
                        @for($i = 0; $i < 5; $i++) @if ($i < $rating) <div wire:click="setRating({{ $i }})"><i class="fa-solid fa-star fa-xl" style="color: #ffae00;"></i></div>
                    @else
                    <div wire:click="setRating({{ $i }})"><i class="fa-regular fa-star fa-xl" style="color: #ffae00;"></i></div>
                    @endif
                    @endfor
                </div>
                <div class="form-group mt-3">
                    <label for="">Review</label>
                    <textarea name="review" wire:model="review" id="" class="form-control"
                        placeholder="Review"></textarea>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm custom-btn-outline" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm custom-btn">Simpan Review</button>
        </div>
        </form>
    </div>
</div>
</div>

@push('js')
<script>
    window.addEventListener('contentChange', function(){

        })

        Livewire.on('finishReview',(status, message) => {
            alert(status, message)
            $('.modal').modal('hide')
        })
</script>
@endpush