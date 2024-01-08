<div class="bg-white shadow mb-5" style="border-radius: 15px">
    <div class="pt-5 text-center">
        <div class="text-center mb-5">
            <span class="fs-2 fw-bold text-white circle-profile-default text-uppercase"
                style="">{{ substr(Auth::user()->name, 0, 2) }}</span>
        </div>
        <small class="d-block mb-3 fw-light">Daily Dose Account: {{ sprintf('%08d', Auth::user()->id) }}</small>
        <small class="d-block mb-4 fw-light">Joined since {{ Auth::user()->user_join }}</small>
    </div>
    <hr>
    <div class="p-4">
        <a href="{{ route('profile') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'profile') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-regular fa-circle-user"></i>
                </div>
                <div class="col-md col">
                    My Profile
                </div>
            </div>
        </a>
        <a href="{{ route('profile.bookings') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'bookings') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-regular fa-credit-card"></i>
                </div>
                <div class="col-md col">
                    My bookings
                </div>
            </div>
        </a>
        <a href="{{ route('profile.packages') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'packages') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <div class="col-md col">
                    My packages
                </div>
            </div>
        </a>
        <a href="{{ route('profile.voucher') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'voucher') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div class="col-md col">
                    My Voucher
                </div>
            </div>
        </a>
        {{-- <a href="{{ route('profile.memberships') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'memberships') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-users-rectangle"></i>
                </div>
                <div class="col-md col">
                    My memberships
                </div>
            </div>
        </a> --}}
        <a href="{{ route('profile.billing') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'billing') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-comments-dollar"></i>
                </div>
                <div class="col-md col">
                    Billing
                </div>
            </div>
        </a>
        {{-- <a href="{{ route('profile.notification-settings') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'notification-settings') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div class="col-md col">
                    Notification settings
                </div>
            </div>
        </a> --}}
        {{-- <a href="{{ route('profile.waiver-form') }}" class="menu-profile">
            <div class="row mb-3 align-items-center @if ($active == 'waiver-form') active @endif">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-clipboard"></i>
                </div>
                <div class="col-md col">
                    Waiver Form
                </div>
            </div>
        </a> --}}
        <div class="menu-profile">
            <div class="row mb-3 align-items-center">
                <div class="col-md-2 col-2 fs-5">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                <div class="col-md col">
                    <form action="{{ route('logout') }}" method="POST" id="form_logout">
                        @csrf
                        <label for="#btn_logout" wire:click='$emit("onClickLogout")' style="cursor: pointer">
                            Logout
                        </label>
                        <button type="submit" class="" hidden id="btn_logout"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {

        });

        Livewire.on('onClickLogout', () => {
            $('#form_logout').submit();
        })
    </script>
@endpush
