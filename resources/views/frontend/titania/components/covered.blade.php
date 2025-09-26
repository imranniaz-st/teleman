<section id="business-types" class="section is-medium is-skewed-sm">

        <div class="floating-bubbles">
            <div class="bubble">
                <img class="bubble-1st levitate" src="{{ asset('frontend/titania/assets/img/graphics/icons/bub-purple.svg') }}" alt="">
            </div>
            <div class="bubble">
                <img class="bubble-2nd levitate" src="{{ asset('frontend/titania/assets/img/graphics/icons/bub-blue.svg') }}" alt="">
            </div>
            <div class="bubble">
                <img class="bubble-3rd levitate" src="{{ asset('frontend/titania/assets/img/graphics/icons/bub-blue.svg') }}" alt="">
            </div>
            <div class="bubble">
                <img class="bubble-4th levitate" src="{{ asset('frontend/titania/assets/img/graphics/icons/bub-blue.svg') }}" alt="">
            </div>
        </div>

        <div class="container is-reverse-skewed-sm">
            <!-- Title -->
            <div class="section-title-wrapper has-text-centered">
                <div class="bg-number">3</div>
                <h2 class="section-title-landing editable is-modified" data-cid="25" tabindex="1">
                    {{ saasContent(25) ?? 'We got you covered' }}
                </h2>
                <h4 class="editable is-modified" data-cid="26" tabindex="1">
                    {{ saasContent(26) ?? 'Every business matters, learn how we handle it.' }}
                </h4>
            </div>

            <!-- Content -->
            <div class="content-wrapper">
                <div class="columns is-vcentered">
                    <div class="column is-5 is-offset-1">
                        <div class="side-feature-text">
                            <h2 class="feature-headline is-clean editable is-modified" data-cid="27" tabindex="1">
                                {{ saasContent(27) ?? 'Every business matters. We give you tools to succeed.' }}
                            </h2>
                            <p class="editable is-modified" data-cid="28" tabindex="1">
                                {{ saasContent(28) ?? 'Lorem ipsum dolor sit amet, vim quidam blandit voluptaria no, has
                                eu lorem convenire incorrupte.' }}
                            </p>
                            <p class="editable is-modified" data-cid="29" tabindex="1">
                                {{ saasContent(29) ?? 'Lorem ipsum dolor sit amet, vim quidam blandit voluptaria no, has
                                eu lorem convenire incorrupte. Vis mutat altera percipit ad, ipsum
                                prompta ius eu. Sanctus appellantur vim ea.' }}
                                
                            </p>
                            <div class="button-wrap">
                                <a href="{{ route('frontend.pricing') }}" class="button button-cta btn-align raised primary-btn">
                                    {{ translate('Try it free') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Card with icons -->
                    <div class="column is-4 is-offset-1">
                        <div class="flex-card company-types">
                            <div class="icon-group mt-2 mb-2">
                                <i class="fa fa-arrow-right"></i>
                                <span class=" editable is-modified" data-cid="31" tabindex="1">{{ saasContent(31) ?? 'Online stores' }}</span>
                            </div>
                            <div class="icon-group mt-2 mb-2">
                                <i class="fa fa-arrow-right"></i>
                                <span class=" editable is-modified" data-cid="32" tabindex="1">{{ saasContent(32) ?? 'Finance services' }}</span>
                            </div>
                            <div class="icon-group mt-2 mb-2">
                                <i class="fa fa-arrow-right"></i>
                                <span class=" editable is-modified" data-cid="33" tabindex="1">{{ saasContent(33) ?? 'Industry' }}</span>
                            </div>
                            <div class="icon-group mt-2 mb-2">
                                <i class="fa fa-arrow-right"></i>
                                <span class=" editable is-modified" data-cid="34" tabindex="1">{{ saasContent(34) ?? 'Churches' }}</span>
                            </div>
                            <div class="icon-group mt-2 mb-2">
                                <i class="fa fa-arrow-right"></i>
                                <span class=" editable is-modified" data-cid="35" tabindex="1">{{ saasContent(35) ?? 'Logistics' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>