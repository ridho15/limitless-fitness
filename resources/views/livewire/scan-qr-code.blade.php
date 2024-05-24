<div>
    <div class="bg-white fixed-top"
        style="box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.1);
    -webkit-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.1);
    -moz-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.1);">
        <a href="{{ App\Filament\Resources\CheckInOutResource::getUrl('index') }}"
            class="d-flex align-items-center text-dark py-2" style="text-decoration: none">
            <span class="mx-2" href="" class="mx-2 text-dark">
                <i class="fa-solid fa-arrow-left"></i>
            </span>
            <span>Kembali </span>
        </a>
    </div>
    <div class="container mt-md-5">
        <form action="#" wire:submit.prevent="scanImage" method="POST" id="form_scanner">
            @csrf

            <div class="row mb-3 justify-content-center align-items-center">
                <div class="col-md-6 col-12 text-center d-flex flex-column align-items-center" style="width: 100%">
                    <div id="my_camera"></div>
                    <br />

                    <div class="row w-100 justify-content-evenly">
                        <div class="col-md-6 col-6">
                            <button type="button" class="btn custom-btn"
                                wire:click="$emit('take_snapshot')"><small>Ambil Gambar</small>
                                <i class="fa-solid fa-camera"></i></button>
                        </div>
                        <div class="col-md-6 col-6">
                            <button type="button" class="btn custom-btn-outline mx-2"
                                wire:click="$emit('ganti_camera')"><small>Ganti
                                    Camera </small><i class="fa-solid fa-rotate"></i></button>
                        </div>
                    </div>
                    <div class="my-3">
                        <input type="text" name="user_code" class="form-control" onblur="setFocus()"
                            wire:model="user_code" autofocus>
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

    <div wire:ignore.self class="modal fade" id="result_scan" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hasil Scan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" wire:submit.prevent="scanImage">
                    <div class="modal-body">
                        @if ($user)
                            <div class="row mb-2">
                                <div class="col-md-4 col-5">
                                    NIK
                                </div>
                                <div class="col-md col">
                                    : {{ $user->nik }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 col-5">
                                    Nama
                                </div>
                                <div class="col-md col">
                                    : {{ $user->name }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 col-5">
                                    Email
                                </div>
                                <div class="col-md col">
                                    : {{ $user->email }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 col-5">
                                    Status User
                                </div>
                                <div class="col-md col">
                                    : {{ $user->status == 1 ? 'Aktif' : 'Belum / Tidak Aktif' }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 col-5">
                                    Jenis Kelamin
                                </div>
                                <div class="col-md col">
                                    : {{ $user->jenis_kelamin == 1 ? 'Laki-Laki' : 'Perempuan' }}
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ asset('storage/' . $user->ktp) }}" alt="" class="img-fluid">
                                </div>
                                <div class="col-md-6">
                                    <img src="{{ asset('storage/' . $user->selfie) }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom-btn-outline mx-2"
                            data-bs-dismiss="modal"><small>Close</small> <i
                                class="fa-regular fa-circle-xmark"></i></button>
                        <button class="btn custom-btn">Submit <span class="mx-2"><i
                                    class="fa-solid fa-right-to-bracket"></i></span></button>
                    </div>
                </form>
            </div>
        </div>
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
            $('#form_scanner').submit(function(event) {
                event.preventDefault();
            })
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

        Livewire.on('showModal', () => {
            $('#result_scan').modal('show')
        })

        function setFocus() {
            setTimeout(function() {
                $('input[name="user_code"]').focus();
            }, 10)
        }

        document.body.addEventListener('click', setFocus, true)
    </script>
@endpush
