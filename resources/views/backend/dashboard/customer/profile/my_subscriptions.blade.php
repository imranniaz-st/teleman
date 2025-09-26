@extends('backend.layouts.master')

@section('title')
{{ translate('My Subscription') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block-head nk-block-head-lg">
    <div class="nk-block-between-md g-4">
        <div class="nk-block-head-content">
            <div class="nk-block-des">
                <p>{{ translate('Here is list of package/product that you have subscribed.') }}</p>
            </div>
        </div>
        <div class="nk-block-head-content">
            <ul class="nk-block-tools gx-3">
                <li class="order-md-last"><a href="{{ route('frontend.pricing') }}"
                        class="btn btn-white btn-dim btn-outline-primary"><span>{{ translate('Purchase New Plan') }}</span></a></li>
            </ul>
        </div>
    </div>
</div><!-- .nk-block-head -->
<div class="nk-block">


    {{-- ACTIVE PLAN --}}

    <div class="card card-bordered sp-plan">
        <div class="row no-gutters">
            <div class="col-md-8">
                <div class="sp-plan-info card-inner">
                    <div class="row gx-0 gy-3">
                        <div class="col-xl-9 col-sm-8">
                            <div class="sp-plan-name">
                                <h6 class="title text-primary">{{ PackageDetails(userSubscriptionData(Auth::user()->domain)->package_id)->name }}
                                    <span
                                        class="badge badge-{{ userActiveSubscription(userSubscriptionData(Auth::user()->domain)->id) == true ? 'success' : 'light' }} badge-pill">{{ userActiveSubscription(userSubscriptionData(Auth::user()->domain)->id) == true ? 'Active' : 'Expired' }}</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div><!-- .sp-plan-info -->
                <div class="sp-plan-desc card-inner">
                    <ul class="row gx-1">
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Started On') }}</span> {{ userSubscriptionData(Auth::user()->domain)->start_at }}</p>
                        </li>
                        <li class="col-6 col-lg-2">
                            <p><span class="text-soft">{{ translate('Recuring') }}</span> {{ translate('No') }}</p>
                        </li>
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Price') }}</span> {{ price(userSubscriptionData(Auth::user()->domain)->amount) }}</p>
                        </li>
                    </ul>
                </div><!-- .sp-plan-desc -->
            </div><!-- .col -->
            <div class="col-md-4">
                <div class="sp-plan-action card-inner">

                    @if (userActiveSubscription(userSubscriptionData(Auth::user()->domain)->id) == true &&
                        userSubscriptionData(Auth::user()->domain)->package->trial != 1)

                    <div class="sp-plan-btn">
                        <a href="{{ route('frontend.pricing') }}" class="btn btn-secondary"><span>{{ translate('Change Plan') }}</span></a>
                    </div>
                    <div class="sp-plan-note text-md-center">
                        <p>{{ translate('Next Billing on') }} <span>{{ userSubscriptionData(Auth::user()->domain)->end_at }}</span></p>
                    </div>

                    @else

                    <div class="sp-plan-btn">

                        @if (checkUserTrialUsed(Auth::user()->id) == 'true' &&
                        userSubscriptionData(Auth::user()->domain)->package->trial != 1)
                        <form action="{{ route('renew.subscriber.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id"
                                value="{{ userSubscriptionData(Auth::user()->domain)->package_id }}">
                            <button type="submit" class="btn btn-dim btn-white btn-outline-primary">
                                {{ translate('Renew Plan') }}
                            </button>
                        </form>

                        @else

                        <button
                            class="disabled btn btn-dim btn-white btn-outline-secondary"
                            title="You completed the trial period">
                            {{ translate('Not Applicable') }}
                        </button>

                        @endif

                    </div>
                    <div class="sp-plan-note text-md-center">
                        <p>{{ translate('You can not renew the plan') }}.</p>
                    </div>

                    @endif


                </div>
            </div><!-- .col -->
        </div><!-- .row -->
    </div><!-- .sp-plan -->

    {{-- ACTIVE PLAN::END --}}


    @forelse (userPaymentHistory()->unique('package_id') as $subscription)

    @if (userActivePackage($subscription->subscription_id) != $subscription->package_id)

    <div class="card card-bordered sp-plan">
        <div class="row no-gutters">
            <div class="col-md-8">
                <div class="sp-plan-info card-inner">
                    <div class="row gx-0 gy-3">
                        <div class="col-xl-9 col-sm-8">
                            <div class="sp-plan-name">
                                <h6 class="title text-primary">
                                    {{ PackageDetails($subscription->package_id)->name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div><!-- .sp-plan-info -->
                <div class="sp-plan-desc card-inner">
                    <ul class="row gx-1">
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Started On') }}</span> {{ $subscription->start_at }}</p>
                        </li>
                        <li class="col-6 col-lg-2">
                            <p><span class="text-soft">{{ translate('Recuring') }}</span> {{ translate('No') }}</p>
                        </li>
                        <li class="col-6 col-lg-3">
                            <p><span class="text-soft">{{ translate('Price') }}</span> {{ price($subscription->amount) }}</p>
                        </li>
                        <li class="col-6 col-lg-4">
                            <p><span class="text-soft">{{ translate('Access') }}</span> {{ getPackageItems($subscription->package_id) }}
                                {{ Str::pluralStudly('Email', getPackageItems($subscription->package_id)) }} &
                                {{ getPackageBranch($subscription->package_id) }}
                                {{ Str::pluralStudly('SMS', getPackageBranch($subscription->package_id)) }}</p>
                        </li>
                    </ul>
                </div><!-- .sp-plan-desc -->
            </div><!-- .col -->
            <div class="col-md-4">
                <div class="sp-plan-action card-inner">

                    @if (userActiveSubscription($subscription->id) == true)

                    <div class="sp-plan-btn">
                        <a href="{{ route('frontend.pricing') }}" class="btn btn-secondary"><span>{{ translate('Change Plan') }}</span></a>
                    </div>
                    <div class="sp-plan-note text-md-center">
                        <p>{{ translate('Next Billing on') }} <span>{{ $subscription->end_at }}</span></p>
                    </div>

                    @else

                    <div class="sp-plan-btn text-center">

                        @if (checkUserTrialUsed(Auth::user()->id) == 'true' &&
                        PackageDetails($subscription->package_id)->trial != 1)
                        <form action="{{ route('renew.subscriber.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="package_id"
                                value="{{ $subscription->package_id }}">
                            <button type="submit" class="btn btn-dim btn-white btn-outline-primary">
                                {{ translate('Renew Plan') }}
                            </button>
                        </form>

                        <div class="sp-plan-note text-md-center">
                            <p>{{ translate('You can renew the plan anytime') }}.</p>
                        </div>

                        @else

                        <button
                            class="disabled btn btn-dim btn-white btn-outline-secondary"
                            title="You completed the trial period">
                            {{ translate('Not Applicable') }}
                        </button>
                        
                        <div class="sp-plan-note text-md-center">
                            <p>{{ translate('You can not renew this plan') }}.</p>
                        </div>

                        @endif

                    </div>
                    

                    @endif


                </div>
            </div><!-- .col -->
        </div><!-- .row -->
    </div><!-- .sp-plan -->

    @endif

    @empty

    @endforelse

</div><!-- .nk-block -->

@includeWhen(true, 'backend.dashboard.customer.components.support')

@endsection

@section('js')

@endsection
