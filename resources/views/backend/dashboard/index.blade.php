@extends('backend.layouts.master')

@section('title')
    {{ translate('Welcome Back') }}, {{ Str::headline(Auth::user()->name) }}
@endsection

@section('css')
    
@endsection
    
@section('content')

@if (Auth::user()->role == 'admin')
    @includeWhen(true, 'backend.dashboard.admin.index')
@endif

@if (Auth::user()->role == 'customer')
    @includeWhen(true, 'backend.dashboard.customer.index')
@endif

<input type="hidden" id="curr" value="{{ symbol() }}">
            
@endsection

@section('js')
    <script src="{{ asset('backend/assets/js/charts/gd-default.js?ver=2.4.0') }}"></script>
    <script src="{{ asset('backend/assets/js/charts/gd-analytics.js') }}"></script>
    <script src="{{ asset('backend/assets/js/libs/jqvmap.js?ver=2.4.0') }}"></script>

    <script>
        "use strict";

        // active Subscription
        var activeSubscription = {
            labels: [
                @foreach(dashboard_data()['active_subscription_month_based'] as $data)
                    '{{ $data->month }}',
                @endforeach
            ],
            dataUnit: '',
            stacked: true,
            datasets: [{
            label: "Active Subscription",
            color: [
                @foreach(dashboard_data()['active_subscription_month_based'] as $data)
                    @if (dashboard_data()['current_month_name'] ==  $data->month) 
                        "#798bff",
                    @else
                        NioApp.hexRGB("#798bff", .2),
                    @endif
                @endforeach
            ],
            data: [
                @foreach(dashboard_data()['active_subscription_month_based'] as $data)
                    {{ $data->total }},
                @endforeach
                ]
            }]
        };


        // average Subscription
        var totalSubscription = {
            labels: [
                @foreach(dashboard_data()['average_subscription_month_based'] as $data)
                    '{{ $data->month }}',
                @endforeach
            ],
            dataUnit: '',
            stacked: true,
            datasets: [{
            label: "Average Subscription",
            color: [
                @foreach(dashboard_data()['average_subscription_month_based'] as $data)
                    @if (dashboard_data()['current_month_name'] ==  $data->month) 
                        "#798bff",
                    @else
                        NioApp.hexRGB("#798bff", .2),
                    @endif
                @endforeach
            ],
            data: [
                @foreach(dashboard_data()['average_subscription_month_based'] as $data)
                    {{ $data->total }},
                @endforeach
                ]
            }]
        };

        // salesRevenue
        var salesRevenue = {
            labels: [
                @foreach(dashboard_data()['sales_revenue_month_based'] as $data)
                    '{{ $data->month }}',
                @endforeach
            ],
            dataUnit: '{{ curr() }}',
            stacked: true,
            datasets: [{
            label: "Sales Revenue",
            color: [
                @foreach(dashboard_data()['sales_revenue_month_based'] as $data)
                    @if (dashboard_data()['current_month_name'] ==  $data->month) 
                        "#798bff",
                    @else
                        NioApp.hexRGB("#798bff", .2),
                    @endif
                @endforeach
            ],
            data: [
                @foreach(dashboard_data()['sales_revenue_month_based'] as $data)
                    {{ $data->total }},
                @endforeach
                ]
            }]
        };

        // salesOverview
        var salesOverview = {
            labels: [@foreach(thirty_days_dates() as $data)
                    "{{ Carbon\Carbon::parse($data)->format('d') }}",
                @endforeach
            ],
            dataUnit: '{{ curr() }}',
            lineTension: 0.4,
            datasets: [{
            label: "Sales Overview",
            color: "#798bff",
            background: NioApp.hexRGB('#798bff', .35),
            data: [
                @foreach(dashboard_data()['sales_total_per_day_wise_this_month'] as $data)
                    {{ onlyPrice($data) }},
                @endforeach
                ]
            }]
        };

        // worldMap
        var worldMap = {
            map: 'world_en',
            data: {
                @foreach(get_users_by_country() as $data)
                    @if ($data->country_code != null)
                        "{{ $data->country_code }}": "{{ $data->total }}",
                    @endif
                @endforeach
            }
        };
       
    </script>
@endsection