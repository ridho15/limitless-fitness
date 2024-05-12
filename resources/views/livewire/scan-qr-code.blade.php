<div>
    <div class="bg-white fixed-top"
        style="box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.1);
    -webkit-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.1);
    -moz-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.1);">
        <a href="{{ App\Filament\Resources\CheckInOutResource::getUrl('index') }}" class="d-flex align-items-center text-dark py-2">
            <span class="mx-2" href="" class="mx-2 text-dark">
                <i class="fa-solid fa-arrow-left"></i>
            </span>
            <span>Kembali </span>
        </a>
    </div>
    <div class="container mt-3">
        <form method="POST" wire:submit="scanImage">
            @csrf
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 col-12 text-center d-flex flex-column align-items-center" style="width: 100%">
                    <div id="my_camera"></div>
                    <br />
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <button type="button" class="btn custom-btn" wire:click="$emit('take_snapshot')">Ambil
                                Gambar <i class="fa-solid fa-camera"></i></button>
                        </div>
                        <div class="col-md-6 col-6">
                            <button type="button" class="btn custom-btn-outline"
                                wire:click="$emit('ganti_camera')">Ganti
                                Camera <i class="fa-solid fa-rotate"></i></button>
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
                    <button class="btn custom-btn">Submit <span class="mx-2"><i
                                class="fa-solid fa-right-to-bracket"></i></span></button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
    <script>
        var cameraFacingMode = "user";
        $(document).ready(function() {
            initWebcam();
        });

        function initWebcam() {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                var width = $(window).width() > 640 ? 640 : $(window).width();
                var height = width + 100;
                console.log("is mobile device");
            } else {
                // false for not mobile device
                console.log("is not mobile device");
                var width = $(window).width() > 640 ? 640 : $(window).width();
                var height = width * 75 / 100;
            }
            Webcam.set({
                width: width,
                height: height,
                dest_width: width,
                dest_height: height,
                crop_width: width,
                crop_height: height,
                image_format: 'jpeg',
                jpeg_quality: 90,
                constraints: {
                    facingMode: cameraFacingMode
                }
            });
            Webcam.attach('#my_camera');
            height = $(window).height();
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
