<footer class="bg-color-custom pb-3 pt-5 mt-auto" id="footer">
    <div class="container">
        {{-- <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="fw-bold text-grey mb-5">Daily Dose Pilates</h3>
                <img src="{{ asset('assets/images/logo.png') }}" width="200" height="100" class="mb-4"
                    alt="logo" style="object-fit: contain">
                <div class="d-flex justify-content-center align-items-center">
                    <small class="mx-2">Contact us</small>
                    <small class="mx-2">FAQ</small>
                    <small class="mx-2">Terms Of Use</small>
                </div>
            </div>
        </div>
        <hr> --}}
        <div class="row">
            <div class="col-md-6 fw-light text-white">
                Copyright © 2023 <strong>MyHassee.com</strong>
            </div>
            <div class="col-md-6 text-end fw-light">
                <div class="country-timezone">
                    <span class="fi fi-id"></span>
                    <p class="text-white">Indonesia (GMT +07:00) -
                        {{ $location ? $location->nama : 'Your Location' }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>
