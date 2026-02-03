<section class="pricing_plans_section" id="kelas">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pricing_plans_content">
                    <h5>What We Offer</h5>
                    <h2>Our Pricing Plans</h2>
                    <p>Molestiae non recusandae itaque earum rerum hic teneaur a sapiente delectus, rae aut
                        reiciendis officia deserunt mollitia animi omnis dolor</p>
                </div>
            </div>
        </div>
        <div class="row" data-aos="fade-up">
            @foreach ($listKelas as $item)
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="pricing_plans_box_content">
                        <div class="pricing_plans_box_upper_portion">
                            <figure class="pricing_plans_image mb-0">
                                <img src="{{ storage_url($item->foto) }}" alt="" class="img-fluid">
                            </figure>
                            <div class="pricing_plans_box_image_content">
                                <figure class="mb-0">
                                    <img src="{{ asset('/assets/images/pricing_plan_icon_1.png') }}" alt=""
                                        class="img-fluid">
                                </figure>
                            </div>
                        </div>
                        <div class="pricing_plans_box_lower_portion">
                            <h3>{{ $item->nama_kelas }}</h3>
                            <div class="text-center">
                                <small class="text-center">Instruktur : <span class="font-weight-bold">
                                        {{ $item->user->name }}
                                    </span></small>
                            </div>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>{{ $item->jumlah_pertemuan }}
                                    Kali Pertemuan
                                </li>
                                <li>
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>Diskon {{ $item->diskon }}%
                                </li>
                                <li>
                                    <i class="fa-solid fa-check" aria-hidden="true"></i>{{ $item->keterangan }}
                                </li>
                            </ul>
                            <div class="pricing_plans_span_wrapper">
                                <del>Rp.{{ number_format($item->harga_real, 0, ',', '.') }}</del>
                                <span class="price">Rp.{{ number_format($item->harga, 0, ',', '.') }}</span>
                                <span class="per_month">/{{ $item->jumlah_pertemuan }} Kali Pertemuan</span>
                            </div>
                            <div class="btn_wrapper">
                                <a class="enroll_now_btn text-decoration-none"
                                    href="{{ route('order-kelas', ['kelas' => \Crypt::encryptString($item->id)]) }}">Enroll
                                    Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
