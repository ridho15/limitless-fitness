@extends('base-2.layout')

@section('content')
    <section class="my-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    @livewire('profile.left-bar', ['active' => $active])
                </div>
            </div>
        </div>
    </section>
@endsection
