@extends('backend.layouts.master')

@section('title')
{{ translate('Call Log Of') }} ⇢ {{ provider_info($account_sid)->phone }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block">

    <div class="card card-bordered sp-plan">
        <div class="row no-gutters">
            <div class="col-md-8">
                <div class="sp-plan-info card-inner">
                    <div class="row gx-0 gy-3">
                        <div class="col-xl-9 col-sm-8">
                            <div class="sp-plan-name">
                                <h3>{{ $call->sid }}</h3>
                                <p>{{ translate('Direction') }}: <span class="text-base">{{ $call->direction }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sp-plan-desc card-inner">
                    <ul class="row gx-1">
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Started On') }}</span> {{ Carbon\Carbon::parse($call->startTime) }}</p>
                        </li>
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Ended On') }}</span> {{ Carbon\Carbon::parse($call->endTime) }}</p>
                        </li>
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Price') }}</span> {{ $call->price }}{{ $call->priceUnit }}</p>
                        </li>
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Status') }}</span> {{ $call->status }}</p>
                        </li>
                    </ul>
                    <ul class="row gx-1">
                     
                        <li class="col-4 col-lg-4">
                            <p><span class="text-soft">{{ translate('Phone Number SID') }}</span> {{ $call->phoneNumberSid }}</p>
                        </li>
                        <li class="col-4 col-lg-4">
                            <p><span class="text-soft">{{ translate('Queue Time') }}</span> {{ $call->queueTime }}</p>
                        </li>
                        <li class="col-4 col-lg-4">
                            <p><span class="text-soft">{{ translate('Duration') }}</span> {{ $call->duration }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="sp-plan-action card-inner">
                    <div class="sp-plan-btn"><a class="btn btn-secondary"
                            href="{{ route('dashboard.provider.download_recording', [$call->sid, $account_sid]) }}" target="_blank"><span>{{ translate('Call Record') }}</span></a></div>
                    <div class="sp-plan-note text-md-center">
                        <p>{{ translate('To') }}: <span>{{ $call->toFormatted }}</span></p>
                        <p>{{ translate('From') }}: <span>{{ $call->fromFormatted }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card sp-plan">
        <div class="row no-gutters">
            <div class="col-md-12">
                <audio controls class="w-100">
                    <source src="{{ $recording }}" type="audio/mpeg">
                    {{ translate('Your browser does not support the audio element.') }}
                </audio>
            </div>
         
        </div>
    </div>
   
</div>


@endsection

@section('js')

@endsection
