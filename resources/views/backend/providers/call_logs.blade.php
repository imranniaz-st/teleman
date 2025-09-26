


@extends('backend.layouts.master')

@section('title')
{{ translate('Call Logs') }} ⇢ {{ provider_info($account_sid)->phone }}
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
    <h3 class="h5 text-center">{{ translate('Fetching call logs from Twilio.') }}</h3>
    <h3 class="h5 text-center">{{ translate('This can take time, do not refresh the page.') }}</h3>
</div>

    <div class="card card-preview">
        <div class="card-inner" id="callLogs">
            <table class="table">
            <thead>
                <tr>
                <th scope="col">{{ translate('SL') }}</th>
                <th scope="col">{{ translate('Call Logs') }}</th>
                <th scope="col">{{ translate('Account SID') }}</th>
                <th scope="col">{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 20; $i++)
                <tr>
                    <th scope="row">{{ $i }}</th>
                    <td>{{ $account_sid }}</td>
                    <td>{{ translate('Status') }}</td>
                    <td>{{ translate('Action') }}</td>
                </tr>
                @endfor
                
            </tbody>
            </table>
        </div>
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
        var url = "{{ route('dashboard.provider.call.logs.ajax', $account_sid) }}";
        var instance = $('#callLogs').scheletrone({ // initialize the plugin
            url   : url, // url to load data from
        });
    });

</script>
@endsection
