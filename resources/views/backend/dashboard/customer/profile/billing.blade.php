@extends('backend.layouts.master')

@section('title')
{{ translate('Billing Information') }}
@endsection

@section('css')

@endsection

@section('content')

<ul class="nk-nav nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.profile.information') }}">{{ translate('Personal') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.profile.billing') }}">{{ translate('Billing') }}</a>
    </li>
</ul><!-- nav-tabs -->

<div class="nk-block">
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h5 class="nk-block-title">{{ translate('Current Subscription') }}</h5>
            <div class="nk-block-des">
                <p>{{ translate('Details about your current subscription and billing information') }}.</p>
            </div>
        </div>
    </div><!-- .nk-block-head -->

    @includeWhen(true, 'backend.dashboard.customer.components.current_plan')

    <!--  Another Sub Section -->
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h5 class="nk-block-title">{{ translate('Billing Cycle') }}</h5>
            <div class="nk-block-des">
                <p>{{ translate('Your subscription renews on') }} {{ billingPlan()->created_at->format('M d, Y') }} <span
                        class="fs-13px text-soft">({{ convertdaysToWeeksMonthsYears(userSubscriptionDateEndIn(Auth::user()->domain)) }}).</span>
                </p>
            </div>
        </div>
    </div><!-- Nk-Block-head -->
    <div class="card card-bordered">
        <div class="card-inner-group">
            <div class="card-inner">
                <div class="between-center flex-wrap flex-md-nowrap g-3">
                    <div class="nk-block-text">
                        <h6>{{ Str::headline(billingPlan()->package->range_type) }} {{ translate('Subscription') }}</h6>
                        <ul class="list-inline list-col2 text-soft">
                            <li>{{ translate('Next payment') }}: <strong class="text-base">{{ price(billingPlan()->package->price) }}
                                    {{ curr() }}</strong> {{ translate('on') }} <strong
                                    class="text-base">{{ Carbon\Carbon::parse(billingPlan()->end_at)->format('M d, Y') }}</strong>
                            </li>
                            <li>{{ translate('Last payment made') }}: {{ Carbon\Carbon::parse(billingPlan()->start_at)->format('M d, Y') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- .nk-card-inner -->
        </div>
    </div><!-- .card -->
    <!--  Another Sub Section -->

</div><!-- .nk-block -->

@includeWhen(true, 'backend.dashboard.customer.components.support')

@endsection

@section('js')

@endsection
