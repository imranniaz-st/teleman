@extends('frontend.layouts.master')

@section('title')
    {{ translate('Payment Success') }}
@endsection

@section('css')
    
@endsection

@section('content')

<section class="comming-soon-wrapper comming-soon-4 pt-100">
        <div class="bg-shapes">
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape11.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape12.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape13.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape14.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape15.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape16.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape17.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape18.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape19.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/shape20.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/star.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/star.svg') }}" alt="Shape">
          </div>
          <div class="shape">
            <img src="{{ asset('frontend/assets/images/comming_soon/star.svg') }}" alt="Shape">
          </div>
        </div>

        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <div class="comming-soon-inner">
                <a href="{{ route('frontend') }}" class="logo">
                  <img src="{{ logo() }}" alt="Logo">
                </a>

                <h1 class="page-title">{{ translate('Thank You') }}, <br>{{ getUserInfo(Auth::user()->id)->name ?? null}}</h1>

                <p class="page-description">
                  {{ translate('We have sent you an email to') }} <strong>{{ getUserInfo(Auth::user()->id)->email ?? null}}</strong> 
                  <br>
                  {{ translate('You renew has been successfully completed.') }}
              </div>
            </div>

            <div class="col-lg-4">
              <div class="right-img">
                <img src="{{ asset('frontend/assets/images/comming_soon/bg3.svg') }}" alt="Bg Shape" class="">
              </div>
            </div>
          </div>
        </div>
      </section>
    
@endsection

@section('js')
    
@endsection