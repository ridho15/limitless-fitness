<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modal_location_studio" tabindex="-1"
        aria-labelledby="modal_location_studio" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Silahkan Pilih Lokasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" wire:submit.prevent='updateStudioUser'>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="" class="form-label">Location</label>
                            <select name="id_location" wire:model="id_location" class="form-control" required>
                                <option value="">Pilih</option>
                                @foreach ($listLocation as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_location')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- <div class="form-group mb-3">
                            <label for="" class="form-label">Studio</label>
                            <select name="id_studio" wire:model="id_studio" class="form-control" required>
                                <option value="">Pilih</option>
                                @foreach ($listLocationStudio as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('id_studio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom-btn-outline" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn custom-btn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            select2()
        });

        window.addEventListener('contentChange', function() {
            select2()
        })

        function select2() {
            $('select[name="id_location"]').select2({
                theme: 'bootstrap-5'
            })

            $('select[name="id_studio"]').select2({
                theme: 'bootstrap-5'
            })

            $('select[name="id_location"]').on('change', function() {
                Livewire.emit('changeLocation', $(this).val())
            })

            $('select[name="id_studio"]').on('change', function() {
                Livewire.emit('changeLocationStudio', $(this).val())
            })
        }

        Livewire.on('finishUpdate', () => {
            $('#modal_location_studio').modal('hide')
        })
    </script>
@endpush
