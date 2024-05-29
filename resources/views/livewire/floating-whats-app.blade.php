<div>
    <a href="https://wa.me/+{{ session()->get('phone_number') ? '62' . session()->get('phone_number') : '6289654808887' }}"
        target="_blank" class="rounded-circle"
        style="position: fixed; bottom: 15px; right: 15px; background-color: #000000; padding: 15px; z-index: 1000;">
        <img src="{{ asset('assets/images/icon-whatsapp.png') }}" width="40" height="40" alt=""
            style="object-fit: contain">
    </a>
</div>
