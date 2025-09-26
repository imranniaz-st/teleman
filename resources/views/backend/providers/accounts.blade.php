


@extends('backend.layouts.master')

@section('title')
{{ translate('Provider Accounts') }}
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
    <h3 class="h5 text-center">{{ translate('Fetching data from Twilio.') }}</h3>
    <h3 class="h5 text-center">{{ translate('This can take time, do not refresh the page.') }}</h3>
</div>

    
<div class="row g-gs" id="myDIV">
    @for ($i = 0; $i < 6; $i++)
        <div class="col-sm-6 col-xl-4">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="project">
                        <div class="project-head">
                                <div class="user-avatar sq bg-purple"></div>
                                <div class="project-info">
                                    <h6 class="title"></h6>
                                    <span class="sub-text">{{ translate('Phone Number') }}</span>
                                </div>
                        </div>
                
                        <div class="project-details">
                            <p>{{ translate('Friendly name') }}: <span class="fw-bold ml-1">
                                {{ translate('Friendly name') }}
                            </span></p>
                            <p>{{ translate('Balance') }}: <span class="text-success fw-bold ml-2">
                                {{ translate('Balance') }}
                                </span>
                            </p>
                        </div>
                        <div class="project-progress">
                            <div class="project-progress-details">
                                <div class="project-progress-task">
                                    <em class="icon ni ni-clock"></em><span>
                                        {{ translate('Hourly Quota') }}</span></div>
                                <div class="project-progress-percent">
                                    {{ translate('left') }}
                                </div>
                            </div>
                            <div class="progress progress-pill progress-md bg-light">
                                <div class="progress-bar w-100">
                                </div>
                            </div>
                        </div>
                        <div class="project-meta">
                            <ul class="project-users g-1">
                                <li>
                                    <span class="badge border-0">{{ translate('Export CSV') }}</span>
                                </li>
                                <li>
                                    <span class="badge border-0">{{ translate('Call logs') }}</span>
                                </li>
                            </ul>
                            <span class="badge border-0">
                                <em class="icon ni ni-clock"></em>
                                <span>{{ translate('Quota Left') }}</span>
                            </span>
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
        toastr.info('Fetching Twilio accounts data...'); // show loading text
        var instance = $('#myDIV').scheletrone({ // initialize the plugin
            url   : "{{ route('dashboard.provider.accounts.ajax') }}", // url to load data from
        });
    });

</script>

@endsection
