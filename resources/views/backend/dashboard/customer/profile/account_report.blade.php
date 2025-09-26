@extends('backend.layouts.master')

@section('title')
{{ translate('Account Reports') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block-head nk-block-head-lg">

    <div class="nk-block-between-md g-4">
        <div class="nk-block-head-content">
            <h2 class="nk-block-title fw-normal text-capitalize lead">{{ user_subscription_data($domain)['subscription_name'] }}
            </h2>
            <h2 class="nk-block-title font-weight-bolt">{{ user_subscription_data($domain)['domain'] }}</h2>
            <div class="nk-block-des">
                <p>{{ translate('Your subscription renews on') }} {{ Carbon\Carbon::parse(billingPlan($domain)->end_at)->format('M d, Y') }} 
                    <span class="text-soft">
                        ({{ convertdaysToWeeksMonthsYears(userSubscriptionDateEndIn($domain)) }})
                    </span>
                    <span class="text-primary"></span>
                </p>
            </div>
        </div>
        <div class="nk-block-head-content">
            <ul class="nk-block-tools justify-content-md-end g-4 flex-wrap">
                <li class="order-md-last d-none">
                    <a href="javascript:;" class="btn btn-auto btn-dim btn-danger" data-toggle="modal"
                        data-target="#subscription-cancel"><em class="icon ni ni-cross"></em><span>
                            {{ translate('Cancel Subscription') }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-bordered">
                <div class="card-inner-group">
                    <div class="card-inner">
                        <div class="sp-plan-head">
                            <h6 class="title">{{ translate('Plan Details') }}</h6>
                        </div>
                        <div class="sp-plan-desc sp-plan-desc-mb">
                            <ul class="row gx-1">
                                <li class="col-sm-4">
                                    <p><span class="text-soft">{{ translate('Started On') }}</span>
                                        {{ Carbon\Carbon::parse(billingPlan($domain)->start_at)->format('M d, Y') }}</p>
                                </li>
                                <li class="col-sm-4">
                                    <p><span class="text-soft">{{ translate('Price') }}</span> {{ price(billingPlan($domain)->amount) }}
                                        /{{ billingPlan($domain)->package->range_type }}</p>
                                </li>
                                <li class="col-sm-4">
                                    <p><span class="text-soft">{{ translate('Access') }}</span>
                                        <a href="javascript:;" class="text-primary" data-toggle="modal"
                                            data-target="#modalDefault">{{ translate('Click to view') }}</a>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div><!-- .card-inner -->

                    <div class="card-inner">
                        <div class="sp-plan-desc sp-plan-desc-mb">
                            <ul class="row gx-1">
                                <li class="col-sm-4">
                                    <p><span class="text-soft">{{ translate('Last Payment') }}</span>
                                        {{ translate('Paid at') }} {{ Carbon\Carbon::parse(billingPlan($domain)->start_at)->format('M d, Y') }}
                                    </p>
                                </li>
                                <li class="col-sm-4">
                                    <p><span class="text-soft lead">{{ Str::upper(billingPlan($domain)->payment_status) }}</p>
                                </li>
                                <li class="col-sm-4">
                                    <p>
                                        <span class="text-soft amount">{{ price(billingPlan($domain)->amount) }}</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div><!-- .card-inner -->

                </div><!-- .card-inner-group -->
            </div><!-- .card -->
        </div><!-- .col -->

    </div>
</div><!-- .nk-block -->

@includeWhen(true, 'backend.dashboard.customer.components.support')

<!-- Modal Content Code -->
<div class="modal fade zoom" tabindex="-1" id="modalDefault">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header">
                <h5 class="modal-title text-capitalize">{{ user_subscription_data($domain)['subscription_name'] }}</h5>
            </div>
            <div class="modal-body">

                <table class="table table-features">
                    <thead class="tb-ftr-head thead-light">
                        <tr class="tb-ftr-item">
                            <th class="tb-ftr-info">{{ translate('Features') }}</th>
                            <th class="tb-ftr-plan">{{ user_subscription_data($domain)['subscription_name'] }}</th>
                        </tr><!-- .tb-ftr-item -->
                    </thead>
                    <tbody class="tb-ftr-body">
                        @forelse (activeFeatures() as $feature)
                        <tr class="tb-ftr-item">
                            <td class="tb-ftr-info">{{ Str::upper($feature->name) }}</td>
                            <td class="tb-ftr-plan"><em
                                    class="icon ni ni-{{ checkFeatureExists(user_subscription_data($domain)['package_id'], $feature->id) == 'true' ? 'check-thick' : 'na' }}"></em>
                                <span class="plan-name">{{ $feature->name }}</span></td>
                        </tr><!-- .tb-ftr-item -->
                        @empty

                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>

@endsection

@section('js')

@endsection
