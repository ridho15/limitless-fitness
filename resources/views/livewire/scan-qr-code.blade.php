<div class="container">
    <form method="POST" wire:submit="scanImage">
        @csrf
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6 col-12 text-center d-flex flex-column align-items-center" style="width: 100%">
                <div id="my_camera"></div>
                <br />
                <div class="row">
                    <div class="col-md-6 col-6">
                        <button type="button" class="btn custom-btn" wire:click="$emit('take_snapshot')">Ambil
                            Gambar</button>
                    </div>
                    <div class="col-md-6 col-6">
                        <button type="button" class="btn custom-btn-outline" wire:click="$emit('ganti_camera')">Ganti
                            Camera</button>
                    </div>
                </div>
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
                <div id="init"></div>
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
        var cameraFacingMode = "user";
        $(document).ready(function() {
            initWebcam();
        });

        function initWebcam() {
            var width = $(window).width() > 640 ? 640 : $(window).width();
            var height = width * 75 / 100;
            Webcam.set({
                width: 490,
                height: 350,
                dest_width : width,
                dest_height : height,
                image_format: 'jpeg',
                jpeg_quality: 90,
                constraints: {
                    facingMode: cameraFacingMode
                }
            });
            Webcam.attach('#my_camera');
            $('#init').html(width + "---" + height);
        }
        window.addEventListener('contentChange', function() {
            initWebcam();
        })

        Livewire.on('ganti_camera', () => {
            cameraFacingMode = (cameraFacingMode == 'user') ? 'environment' : 'user';
            console.log(cameraFacingMode);
            Webcam.reset();
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
