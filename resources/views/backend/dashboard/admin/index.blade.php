@if (env('MAIL_USERNAME') == null)
<div class="alert alert-pro alert-warning alert-dismissible">
<div class="alert-text">
<h6>{{ translate('SMTP configuration is needed') }}</h6>
<p>{{ translate('Please setup the SMTP settings before publicity your application.') }} </p>
</div>
<button class="close" data-dismiss="alert"></button>
</div>

@endif

@if (env('SSL_COMMERZ') == null || env('BRAINTREE') == null || env('STRIPE') == null)

<div class="alert alert-pro alert-warning alert-dismissible">
<div class="alert-text">
<h6>{{ translate('Setup payment gateway') }}</h6>
<p>{{ translate('Please setup at list one payment gateway before publicity your application.') }} </p>
</div>
<button class="close" data-dismiss="alert"></button>
</div>

@endif


<div class="nk-block">
    <div class="row g-gs">
        <div class="col-sm-6">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Active Subscriptions') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="Total active subscription"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ dashboard_data()['total_subscriptions_active'] }}</span>
                            <span class="sub-title">{{ translate('Total') }} <span
                                    class="text-success h6">{{ dashboard_data()['total_subscriptions'] }}</span>
                                {{ Str::pluralStudly('subscription', dashboard_data()['total_subscriptions']) }}</span>
                        </div>
                        <div class="nk-sales-ck">
                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Avg Subscriptions Today') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="Daily Avg. subscription"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ dashboard_data()['average_subscription_today'] }}</span>
                            <span class="sub-title">{{ translate('Total') }} <span
                                    class="text-success h6">{{ dashboard_data()['average_subscription_this_month'] }}</span>
                                {{ translate('in this month') }}</span>
                        </div>
                        <div class="nk-sales-ck">
                            <canvas class="sales-bar-chart" id="totalSubscription"></canvas>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-xl-6">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Sales Revenue') }}</h6>
                            <p>{{ translate('In last 30 days revenue from subscription.') }}</p>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="Revenue from subscription"></em>
                        </div>
                    </div>
                    <div class="align-end gy-3 gx-5 flex-wrap flex-md-nowrap flex-xl-wrap justify-content-center">
                        <div class="nk-sale-data-group flex-md-nowrap g-4">
                            <div class="nk-sale-data">
                                <span class="amount">{{ price(dashboard_data()['total_earning_this_month']) }} 
                                    <span class="change down text-danger d-none">
                                        <em class="icon ni ni-arrow-long-down"></em>
                                        16.93%
                                    </span>
                                </span>
                                <span class="sub-title">{{ translate('This Month') }}</span>
                            </div>
                            <div class="nk-sale-data">
                                <span class="amount">{{ price(dashboard_data()['sales_revenue_this_week']) }} 
                                    <span class="change up text-success d-none">
                                        <em class="icon ni ni-arrow-long-up"></em>4.26%
                                    </span>
                                </span>
                                <span class="sub-title">{{ translate('This Week') }}</span>
                            </div>
                        </div>
                        <div class="nk-sales-ck sales-revenue">
                            <canvas class="sales-bar-chart" id="salesRevenue"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .col -->
        <div class="col-xl-6">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Sales Overview') }}</h6>
                            <p>{{ translate('In 30 days sales of subscription') }}.</p>
                        </div>
                        <div class="card-tools">
                            <div class="dropdown d-none">
                                <a href="javascript:;" class="btn btn-secondary btn-dim d-none d-sm-inline-flex"><em
                                        class="icon ni ni-download-cloud"></em><span>{{ translate('Report') }}</span></a>

                            </div>
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="Sales overview subscription"></em>
                        </div>
                    </div>
                    <div class="nk-sale-data-group align-center justify-between gy-3 gx-5">
                        <div class="nk-sale-data">
                            <span class="amount">{{ price(dashboard_data()['total_sales_overview']) }}</span>
                        </div>
                        <div class="nk-sale-data">
                            <span class="amount sm">{{ dashboard_data()['total_subscriptions'] }}
                                <small>{{ Str::pluralStudly('Subscriber', dashboard_data()['total_subscriptions']) }}</small></span>
                        </div>
                    </div>
                    <div class="nk-sales-ck large pt-4">
                        <canvas class="sales-overview-chart" id="salesOverview"></canvas>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-12">
            <div class="card card-bordered card-full">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title"><span class="mr-2">{{ translate('Invoices') }}</span> <a
                                    href="{{ route('dashboard.profile.billing.history') }}"
                                    class="link d-none d-sm-inline">{{ translate('See History') }}</a></h6>
                        </div>

                    </div>
                </div>
                <div class="card-inner p-0 border-top">

                    <table class="table nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('BILL FOR') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('ISSUE DATE') }}</span></th>
                                <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('DUE DATE') }}</span></th>
                                <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('TOTAL') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>

                            @can('admin')

                            @foreach (customerPaymentHistory()->take(10) as $history)
                            <tr class="nk-tb-item">

                                <td class="nk-tb-col tb-col-md tb-tnx-id">
                                    <a href="{{ route('dashboard.profile.billing.invoice', $history->invoice) }}">
                                        <span>#{{ $history->invoice }}</span>
                                    </a>
                                </td>

                                <td class="nk-tb-col tb-col-xl">
                                    <span
                                        class="font-weight-bold">{{PackageDetails($history->package_id)->name ?? null }}</span>
                                </td>

                                <td class="nk-tb-col tb-col-md">
                                    <span class="tb-status">{{$history->start_at }}</span>
                                </td>

                                <td class="nk-tb-col tb-col-md">
                                    <span class="tb-status">{{$history->end_at }}</span>
                                </td>

                                <td class="nk-tb-col tb-col-md">
                                    <span class="tb-status font-weight-bold">{{ price($history->amount) }}</span>
                                </td>

                                <td class="nk-tb-col tb-col-md">
                                    <span class="tb-status text-success">{{ $history->payment_status }}</span>
                                </td>

                            </tr><!-- .nk-tb-item  -->
                            @endforeach

                            @endcan

                        </tbody>
                    </table>

                </div>
                <div class="card-inner-sm border-top text-center d-sm-none">
                    <a href="{{ route('dashboard.profile.billing.history') }}" class="btn btn-link btn-block">{{ translate('See History') }}</a>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-lg-6">
            <div class="card card-bordered card-full">
                <div class="card-inner border-bottom">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Recent Activities') }}</h6>
                        </div>
                    </div>
                </div>
                <ul class="nk-activity is-scrollable h-325px">
                    @forelse (activities() as $activity)

                    <li class="nk-activity-item">
                        <div class="user-avatar">
                            <span>{{ substr($activity->message, 0, 1) }}</span>
                        </div>
                        <div class="nk-activity-data">
                            <div class="label">{{ $activity->message }}</div>
                            <span class="time">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </li>

                    @empty

                    @endforelse

                </ul>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-lg-6">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title card-title-sm">
                            <h6 class="title">{{ translate('Users by Country') }}</h6>
                        </div>
                    </div>
                    <div class="analytics-map">
                        <div class="vector-map" id="worldMap"></div>
                        <table class="analytics-map-data-list">
                            @foreach(get_users_by_country() as $data)
                                @if ($data->country_code != null)
                                    <tr class="analytics-map-data">
                                        <td class="country">{{ $data->country }}</td>
                                        <td class="percent"><em class="icon ni ni-user-alt"></em>{{ $data->total }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
    </div><!-- .row -->
</div><!-- .nk-block -->
