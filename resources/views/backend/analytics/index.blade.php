@extends('backend.layouts.master')

@section('title')
{{ translate('Analytics') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg position-relative">

<div class="overlay" id="loading" >
    <div class="d-flex justify-content-center mb-2">
      <div class="spinner-border" role="status" >
        <span class="sr-only"></span>
      </div>
    </div>
    <h3 class="h5 text-center">{{ translate('Please wait...') }}</h3>
    <h3 class="h5 text-center">{{ translate('Fetching Twilio Accounts.') }}</h3>
    <h3 class="h5 text-center">{{ translate('This can take time, do not refresh the page.') }}</h3>
</div>

    <div class="row g-gs" id="analytics">
        @for ($i = 0; $i < 4; $i++)

        <div class="col-md-6">
            <div class="card card-bordered card-full">
                <div class="nk-wg1">
                    <div class="nk-wg1-block">
                        <div class="nk-wg1-img">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 90 90"><path d="M40.74,5.16l38.67,9.23a6,6,0,0,1,4.43,7.22L70.08,80a6,6,0,0,1-7.17,4.46L24.23,75.22A6,6,0,0,1,19.81,68L33.56,9.62A6,6,0,0,1,40.74,5.16Z" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M50.59,6.5,11.18,11.73a6,6,0,0,0-5.13,6.73L13.85,78a6,6,0,0,0,6.69,5.16l39.4-5.23a6,6,0,0,0,5.14-6.73l-7.8-59.49A6,6,0,0,0,50.59,6.5Z" fill="#eff1ff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><rect x="15" y="15" width="54" height="70" rx="6" ry="6" fill="#fff" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></rect><line x1="42" y1="77" x2="58" y2="77" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><circle cx="38" cy="77" r="0.5" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10"></circle><line x1="42" y1="72" x2="58" y2="72" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><circle cx="38" cy="72" r="0.5" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10"></circle><line x1="42" y1="66" x2="58" y2="66" fill="none" stroke="#c4cefe" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line><circle cx="38" cy="66" r="0.5" fill="#c4cefe" stroke="#c4cefe" stroke-miterlimit="10"></circle><path d="M46,40l-15-.3V40A15,15,0,1,0,46,25h-.36Z" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M42,22A14,14,0,0,0,28,36H42V22" fill="#e3e7fe" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><polyline points="33.47 30.07 28.87 23 23 23" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polyline><polyline points="25 56 35 56 40.14 49" fill="none" stroke="#6576ff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polyline></svg>
                        </div>
                        <div class="nk-wg1-text">
                            <h5 class="title mb-2">{{ translate('Phone Number') }}</h5>
                                <p>{{ translate('Check out all your call analytics.') }}</p>
                                <br>
                                <p>{{ translate('You can also review the calling status of the campaign.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
        

    </div>

</div>
<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')
<script>
    "use strict";

    // ajax load
    $(document).ready(function() {
        toastr.info('Fetching accounts call logs...'); // show loading text
        var url = "{{ route('dashboard.analytics.index.ajax') }}";
        var instance = $('#analytics').scheletrone({ // initialize the plugin
            url   : url, // url to load data from
        });
    });

</script>
@endsection
