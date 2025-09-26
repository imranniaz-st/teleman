<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="author" content="{{ env('AUTHOR') }}">
<meta name="version" content="{{ env('VERSION') }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">

<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<!-- Fav Icon  -->
<link rel="icon" href="{{ asset(application('site_favicon')) }}" type="image/gif">

{{-- hidden inputs --}}
<input type="hidden" id="dialer_call_duration" name="dialer_call_duration" value="{{ route('dialer.call-duration.store') }}">
<input type="hidden" id="dialer_country_code_exists_in_package" name="dialer_call_duration" value="{{ route('dialer.country.code.exists.in.package') }}">