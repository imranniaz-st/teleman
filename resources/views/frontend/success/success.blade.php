@extends('frontend.titania.layouts.master')

@section('title')
    {{ translate('Payment Success') }}
@endsection

@section('css')
    
@endsection

@section('content')

<!--Nav-->
        @includeWhen(true, 'frontend.titania.components.nav')
        <!--Nav::END-->
        
    <section id="cta" class="section is-medium is-skewed-sm">

      

        <div class="container is-reverse-skewed-sm">
            <!-- Title -->
            <div class="section-title-wrapper has-text-centered">
                <div class="bg-number left-30">{{ translate('success') }}</div>
                <h2 class="section-title-landing">{{ translate('Thank You') }},</h2>
                <h2 class="section-title-landing">{{ translate('Your Payment is successful') }}.</h2>
            </div>

            <div class="content">
                <h2 class="has-text-centered fs-32"><strong>{{ Str::upper(getUserInfo($user_details['user_id'])->name) ?? 'Champ' }}</strong></h2>
                <h4 class="has-text-centered">{{ translate('We have sent you an email to') }} <strong class="text-lowercase">{{ getUserInfo($user_details['user_id'])->email ?? 'that you registered.'}}</strong> </h4>
            </div>
            <div class="has-text-centered is-title-reveal pt-20 pb-20" data-sr-id="3">
                <a href="@auth {{ route('login') }} @else {{ route('frontend') }} @endauth" class="button button-cta btn-align raised primary-btn">
                    @auth
                        {{ translate('Go To Dashboard') }}
                    @else
                        {{ translate('Login') }}
                    @endauth
                </a>
            </div>
        </div>
    </section>
@endsection

@section('js')
    
@endsection