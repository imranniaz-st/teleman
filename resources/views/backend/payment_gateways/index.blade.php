@extends('backend.layouts.master')

@section('title')
{{ translate('Payment Gateways') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="col-12">
    <ul class="row g-gs preview-icon-svg">
        @forelse (extended_menu()['payment_gateways']['sub_menu'] as $key => $gateway)
        
        <li class="col-lg-4 col-sm-6 col-12">
            <div class="preview-icon-box card card-bordered text-center m-auto">
                <div class="preview-icon-wrap text-center">
                    <img src="{{ asset('payment_gatways/' . $key . '.png') }}" alt="{{ Str::upper($key) }}" class="m-auto gateway-logo">
                </div>
                <div> 
                    <span class="title text-white fw-bold fs-17px">
                        {{ Str::upper($key) }}
                    </span>
                </div>
                <a class="fw-medium" href="{{ route($gateway['route_name']) }}">
                    {{ Str::upper($key) }}
                </a>
            </div>
        </li>

        @empty
            
        @endforelse
       
    </ul>
</div>


@endsection

@section('js')

@endsection
