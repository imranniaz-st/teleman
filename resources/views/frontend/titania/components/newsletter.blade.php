<div class="
        section
        is-medium is-relative
        section-feature-grey
      ">

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
        
    <div class="container">
        <!-- Title -->
        <div class="section-title-wrapper has-text-centered">
            <div class="bg-number">5</div>
            <h2 class="section-title-landing editable is-modified" data-cid="36" tabindex="1">{{ translate('Start your Free trial') }}</h2>
            <h4 class="editable is-modified" data-cid="37" tabindex="1">
                {{ translate('Dont struggle anymore to manage tasks. Everything is easy to setup') }}
            </h4>
        </div>
        <!-- /Title -->

        <div class="content-wrapper">
            <form>
                <div class="columns">
                    <div class="column is-8 is-offset-2">
                        <div class="columns is-vcentered">
                            <div class="column is-3">

                                <!-- Form field -->
                                <div class="control-material is-primary">
                                    <input class="material-input" type="text" id="newsletter_name" required="">
                                    <span class="material-highlight"></span>
                                    <span class="bar"></span>
                                    <label>{{ translate('Name') }} *</label>
                                </div>
                            </div>
                            <div class="column is-3">
                                <!-- /Form field -->
                                <div class="control-material is-primary">
                                    <input class="material-input" type="text" id="newsletter_email" required="">
                                    <span class="material-highlight"></span>
                                    <span class="bar"></span>
                                    <label>{{ translate('Email') }} *</label>
                                </div>
                            </div>
                            <div class="column is-3">
                                <!-- Form field -->
                                <div class="control-material is-primary">
                                    <input class="material-input" type="text" id="phone" required="">
                                    <span class="material-highlight"></span>
                                    <span class="bar"></span>
                                    <label>{{ translate('Phone Number') }} *</label>
                                </div>
                            </div>
                            <div class="column is-3">
                                <button type="button" class="
                                                    button button-cta
                                                    btn-align
                                                    primary-btn
                                                    btn-outlined
                                                    is-bold
                                                    rounded
                                                    raised
                                                    no-lh"
                                        onclick="StoreNewsletter()">
                                    {{ translate('Subscribe Now') }}
                                </button>
                                <input type="hidden" id="newsletter_url" value="{{ route('frontend.newsletter.store') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
