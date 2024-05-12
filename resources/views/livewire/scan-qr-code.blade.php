<div class="container">
    <form method="POST" wire:submit="scanImage">
        @csrf
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6 col-12 text-center d-flex flex-column align-items-center" style="width: 100%">
                <div id="my_camera"></div>
                <br />
                <input type=button value="Take Snapshot" wire:click="$emit('take_snapshot')">
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-12 text-center mt-4">
                <div id="results">
                    @if ($image != null)
                        <img src="{{ $image }}" alt="">
                    @else
                        Ambil gambar untuk di scan
                    @endif
                </div>
            </div>
            <div class="col-md-12 text-center">
                <br />
                <button class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            initWebcam();
        });

        function initWebcam() {
            console.log($(window).width());
            var width = $(window).width();
            Webcam.set({
                // width: 500,
                // height: 400,
                width:  width > 500 ? 500 : width,
                height: 350,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_camera');
        }
        window.addEventListener('contentChange', function() {
            initWebcam();
        })
        Livewire.on('take_snapshot', () => {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                result = document.getElementById('results');
                result.innerHTML = '<img src="' + data_uri + '"/>';
                Livewire.emit('setImage', data_uri);
            });
        })

        function take_snapshot() {
            Webcam.snap(function(data_uri) {
                $(".image-tag").val(data_uri);
                result = document.getElementById('results');
                result.innerHTML = '<img src="' + data_uri + '"/>';
            });
        }

        Livewire.on('hasilScan', (status, message) => {
            alert(status, message)
        })
    </script>
@endpush
