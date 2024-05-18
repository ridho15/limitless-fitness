@extends('base-2.layout')

@section('content')
    <section class="my-5 py-5">
        <div class="container px-5">
            <div class="text-center mb-3">
                <img src="{{ asset('vertical/design_7_black.png') }}" alt="" class="img-fluid" style="height: 500px">
            </div>
            <div class="text-center mb-5">
                <h2 class="fw-bold">TERMS AND CONDITIONS</h2>
            </div>
            <ol>
                @foreach ($termsConditions as $index => $item)
                    <li>{{ $index }}
                        <ul>
                            @foreach ($item as $item2)
                                <li>{{ $item2 }} </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endsection
