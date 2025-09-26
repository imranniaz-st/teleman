@extends('frontend.titania.layouts.master')

@section('title')
    {{ translate('Payment Failed') }}
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
                <div class="bg-number left-30">{{ translate('failed') }}</div>
                <h2 class="section-title-landing">{{ translate('Your Payment is failed') }}.</h2>
            </div>

            <div class="content">
                <h4 class="has-text-centered">{{ translate('Please contact to the administrator for any payment issue.') }}</h4>
            </div>
            <div class="has-text-centered is-title-reveal pt-20 pb-20" data-sr-id="3" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.6s cubic-bezier(0.215, 0.61, 0.355, 1) 0.1s, opacity 0.6s cubic-bezier(0.215, 0.61, 0.355, 1) 0.1s; transition: transform 0.6s cubic-bezier(0.215, 0.61, 0.355, 1) 0.1s, opacity 0.6s cubic-bezier(0.215, 0.61, 0.355, 1) 0.1s; ">
                <a href="{{ route('frontend') }}" class="button button-cta btn-align raised primary-btn">
                    {{ translate('Homepage') }}
                </a>
            </div>
        </div>
    </section>
@endsection

@section('js')
    
@endsection