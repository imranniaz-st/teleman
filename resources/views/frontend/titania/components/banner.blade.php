<div class="hero is-app-grey rounded-hero is-fullheight has-shape-layer-1" data-page-theme="green">
    <!--Nav-->
    @includeWhen(true, 'frontend.titania.components.nav')
        <!--Nav::END-->
        <!--Shape Layer-->
        <img class="shape-layer" src="{{ asset('frontend/titania/assets/img/graphics/legacy/wavy-2-green.svg') }}" alt="" />

        <div class="hero-body has-text-centered">
            <div class="container">
                <div class="columns">
                    <div class="column is-8 is-offset-2 is-hero-caption is-centered">
                        <h1 class="title is-1 is-bold is-light editable is-modified" data-cid="1">
                            {{ saasContent(1) ?? 'Personalized experiences your customers love' }}
                        </h1>
                        <h3 class="subtitle is-5 is-light mt-1 editable is-modified" data-cid="2">
                            {{ saasContent(2) ?? ' Try a centralized Voice solution for all your projects.' }}
                        </h3>
                        <div class="buttons">
                            <a href="{{ route('frontend.page.blogs') }}" class="
                                button button-cta
                                is-bold
                                btn-align
                                white-btn
                                is-rounded
                                raised
                            ">
                                {{ translate('Our Blogs') }}
                            </a>
                            <a href="{{ route('frontend.pricing') }}" class="
                                button button-cta
                                is-bold
                                btn-align
                                primary-btn
                                is-rounded
                                raised
                            ">
                                {{ translate('Try For Free') }}
                            </a>
                        </div>
                    </div>
                </div>

                @if (application('site_trailer_url'))
                    <video class="hero-mockup is-smaller has-light-shadow" src="{{ asset(application('site_trailer_url')) }}"
                    playsinline controls autoplay loop></video>    
                @else
                    <img class="hero-mockup is-smaller has-light-shadow" src="{{ asset(application('site_trailer_thumbnail')) }}" alt="" />
                @endif
            </div>
        </div>
</div>
