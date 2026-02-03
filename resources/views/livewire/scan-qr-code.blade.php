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
        <form action="#" wire:submit.prevent="checkBarcode" method="POST" id="form_scanner">
            @csrf
            <div class="row mb-3 justify-content-center align-items-center">
                <div class="col-md-6 col-12 text-center d-flex flex-column align-items-center" style="width: 100%">
                    <div id="my_camera" class="flex-column p-3"
                        style="background-color: #e7e7e7;
                    height: 430px;
                    width: 500px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 10px;">
                    </div>
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
                    <button type="button" wire:click="scanImage" class="btn custom-btn">Submit <span class="mx-2"><i
                                class="fa-solid fa-right-to-bracket"></i></span></button>
                </div>
            </div>
        </form>
    </div>

    <div wire:ignore.self class="modal fade" id="result_scan" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-center">
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
                                    Status User
                                </div>
                                <div class="col-md col">
                                    : {{ $user->status == 1 ? 'Aktif' : 'Belum / Tidak Aktif' }}

                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4 col-5">
                                    Status Paket
                                </div>
                                <div class="col-md col"> :
                                    @if ($orderPaket == null)
                                        <span class="badge text-bg-secondary">Belum Order Paket dan Belum pernah check
                                            in</span>
                                    @elseif($orderPaket->status == 0)
                                        <span class="badge text-bg-danger">Paket tidak aktif</span>
                                    @elseif($orderPaket->expired_date >= now())
                                        <small>Expired At </small> <span
                                            class="badge @if ($orderPaket->expired_date >= now()) text-bg-success @elseif($orderPaket->expired_date < now()) text-bg-danger @endif">{{ \Carbon\Carbon::parse($orderPaket->expired_date)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
                                    @endif
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-md-6">
                                    @if ($user->ktp)
                                        <img src="{{ storage_url($user->ktp) }}" alt=""
                                            class="img-fluid">
                                    @else
                                        <div class="text-center border rounded d-flex align-items-center justify-content-center"
                                            style="height: 300px">
                                            <small>Belum ada KTP</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if ($user->selfie)
                                        <img src="{{ storage_url($user->selfie) }}" alt=""
                                            class="img-fluid">
                                    @else
                                        <div class="text-center border rounded d-flex align-items-center justify-content-center"
                                            style="height: 300px">
                                            <small>Belum ada foto pribadi</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom-btn-outline mx-2"
                            data-bs-dismiss="modal"><small>Close</small> <i
                                class="fa-regular fa-circle-xmark"></i></button>
                        <button class="btn custom-btn" type="submit">Submit <span class="mx-2"><i
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
            // console.log($('#result_scan').hasClass('show'));
            setTimeout(function() {
                if ($('#result_scan').hasClass('show') == false) {
                    $('input[name="user_code"]').focus();
                }
            }, 10)
        }

        document.body.addEventListener('click', setFocus, true)

        async function errorCamera(message) {
            console.log("{{ $no_camera }}");
            if ("{{ $no_camera }}" == false || "{{ $no_camera }}" == "") {
                Livewire.emit('setNoCamera', true)
            }
            $('#my_camera').html('<div class="bg-danger h3 text-white p-3" style="border-radius: 10px">' +
                '<i class="fa-solid fa-triangle-exclamation"></i>' +
                '</div>' +
                '<span id="text_error">' + message + '</span>')
        }
    </script>
@endpush
