<section class="our_team_section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="our_team_content">
                    <h5>Our Staff</h5>
                    <h2>Our Team Members</h2>
                    <p>Quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores nostrum
                        exercitationem ullam corporis suscipit laboriosam</p>
                </div>
            </div>
        </div>
        <div class="row" data-aos="fade-up">
            @foreach ($listInstruktur as $item)
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <div class="our_team_box_content">
                    <div class="our_team_box_upper_portion">
                        <figure class="mb-0"><img src="{{ asset('/assets/images/our_team_1.png') }}" alt=""
                                class="img-fluid"></figure>
                    </div>
                    <div class="our_team_box_lower_portion">
                        <h3>Rayan Collins</h3>
                        <p>Recudia</p>
                        <div class="social_icons">
                            <a href="#" class="text-decoration-none">
                                <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="fa-brands fa-twitter" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="text-decoration-none">
                                <i class="fa-brands fa-instagram mr-0" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>