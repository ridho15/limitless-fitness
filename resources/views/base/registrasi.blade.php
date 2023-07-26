@extends('base.layout')

@section('banner')
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="banner-section-content row" style="padding-top: 20px !important">
                        <div class="col-md-6">
                            <h1 data-aos="fade-up" class="aos-init aos-animate">Register Page</h1>
                            <p data-aos="fade-right" class="aos-init aos-animate">Silahkan isi data untuk melakukan order
                                kelas</p>
                        </div>
                        <div class="col-md-6 shadow-lg p-4 rounded" style="border: 1px solid rgb(255, 255, 255)">
                            <form class="" method="POST" action="{{ route('postRegistrasi') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @if (session()->has('fail') || session()->has('success'))
                                    <div class="p-5 rounded border border-light text-light text-center">
                                        {{ session()->has('success') ? session('success') : session('fail') }}
                                    </div>
                                @endif
                                <div class="form-group mt-3">
                                    <label for="nama" class="text-white">Nama</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Masukkan nama">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="text-white">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" name="email" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-white">We'll never share your email with
                                        anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1" class="text-white">Password</label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                                        placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp" class="text-white">Nomor Hp</label>
                                    <input type="number" name="no_hp" class="form-control" id="no_hp"
                                        placeholder="Masukkan nomor handphone" required>
                                        <small id="noHpHelp" class="form-text text-white">Untuk nomor Hp di berikan awalan +62 atau 08</small>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class="text-white">Alamat</label>
                                    <textarea name="alamat" class="form-control" id="alamat" placeholder="Masukkan alamat" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="foto" class="text-white">Foto</label>
                                    <input type="file" name="foto" class="form-control" placeholder="Pilih foto"
                                        onchange="previewImage(event)" id="foto" accept="image/" required>
                                </div>
                                <div class="text-center">
                                    <img id="preview" src="#" class="rounded text-center" alt="Preview"
                                        style="display: none; object-fit: cover; height: 200px; width: 200px; background-position: center">
                                </div>
                                <button type="submit"
                                    class="btn shadow-lg btn-outline-light btn-block font-weight-bold mt-5">Submit</button>
                                <div class="my-5 text-center text-white">
                                    <small>Do you have an account?
                                        <a href="{{ route('login') }}" class="font-weight-bold no-hover">Log in
                                            ?</a></small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <figure class="banner_left_top_shape left_shape mb-0">
                <img src="{{ asset('/assets/images/banner_left_top_shape.png') }}" alt="" class="img-fluid">
            </figure>
            <figure class="banner_left_bottom_shape left_shape mb-0">
                <img src="{{ asset('/assets/images/banner_left_bottom_shape.png') }}" alt="" class="img-fluid">
            </figure>
            <figure class="banner_right_top_shape right_shape mb-0">
                <img src="{{ asset('/assets/images/banner_right_top_shape.png') }}" alt="" class="img-fluid">
            </figure>
            <figure class="banner_right_bottom_shape right_shape mb-0">
                <img src="{{ asset('/assets/images/banner_right_bottom_shape.png') }}" alt="" class="img-fluid">
            </figure>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

        });

        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function() {
                    preview.src = reader.result;
                    preview.style.display = 'inline-block';
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
