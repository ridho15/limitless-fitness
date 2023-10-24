@if (session()->has('success'))
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <div class="me-2"><i class="fa-solid fa-circle-check"></i></div>
        <div>
            {{ session()->get('success') }}
        </div>
    </div>
@elseif(session()->has('fail'))
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <div class="me-2">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div>
            {{ session()->get('fail') }}
        </div>
    </div>
@endif
