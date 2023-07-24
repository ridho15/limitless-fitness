@extends('base.layout')

@section('banner')
    <section class="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="banner-section-content row" style="padding-top: 20px !important">
                        <div class="col-md-6">
                            <h1 data-aos="fade-up" class="aos-init aos-animate">Login Page</h1>
                            <p data-aos="fade-right" class="aos-init aos-animate">Silahkan login untuk melakukan order kelas
                            </p>
                        </div>
                        <div class="col-md-6 shadow-lg rounded p-3" style="border: 1px solid rgb(255, 255, 255)">
                            <form method="POST" action="{{ route('postLogin') }}">
                                @csrf
                                @if (session()->has('fail') || session()->has('success'))
                                    <div class="p-5 rounded border border-light text-light text-center">
                                        {{ session()->has('success') ? session('success') : session('fail') }}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="text-white">Email address</label>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-white">We'll never share your email with
                                        anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1" class="text-white">Password</label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                                        placeholder="Password">
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" name="remember" id="exampleCheck1">
                                    <label class="form-check-label text-white" for="exampleCheck1">Remember</label>
                                </div>
                                <button type="submit"
                                    class="btn shadow-lg btn-outline-light btn-block font-weight-bold mt-5">Submit</button>
                                <div class="my-5 text-center text-white">
                                    <small>Don't have an account?
                                        <a href="{{ route('registrasi') }}" class="font-weight-bold no-hover">Sign up
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

@section('content')
    <section>

    </section>
@endsection
