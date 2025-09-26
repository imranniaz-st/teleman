@extends('backend.layouts.master')

@section('title')
{{ translate('Analytics Of') }} ⇢ ({{ $phone }})
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block position-relative">

<div class="overlay" id="loading" >
    <div class="d-flex justify-content-center mb-2">
      <div class="spinner-border" role="status" >
        <span class="sr-only"></span>
      </div>
    </div>
    <h3 class="h5 text-center">{{ translate('Please wait...') }}</h3>
    <h3 class="h5 text-center">{{ translate('Fetching ') }}{{ $phone }} {{ translate('analytics data from Twilio.') }}</h3>
    <h3 class="h5 text-center">{{ translate('This can take time, do not refresh the page.') }}</h3>
</div>

    <div class="row g-gs" id="analytics">

        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Total Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Total Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Total Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Completed Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Completed Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Completed Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Queued Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Queued Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Queued Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('No Answered Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('No Answered Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('No Answered Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Initiated Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Initiated Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Initiated Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .card -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Ringing Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Ringing Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Ringing Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Busy Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Busy Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Busy Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Canceled Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Canceled Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Canceled Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Failed Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Failed Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ translate('Failed Calls') }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        
    </div><!-- .row -->
</div><!-- .nk-block -->

<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')
<script>
    "use strict";
    // ajax load
    $(document).ready(function() {
        toastr.info('Fetching accounts data...'); // show loading text
        var url = "{{ route('dashboard.analytics.show.ajax', [$account_sid, $phone]) }}";
        var instance = $('#analytics').scheletrone({ // initialize the plugin
            url   : url, // url to load data from
        });
    });
</script>
@endsection
