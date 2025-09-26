@if (teleman_config('dashboard_ui') == 'EXTENDED')
<link rel="stylesheet" href="{{ asset('backend/assets/css/dashlite19ce.css') }}">
@else
<link rel="stylesheet" href="{{ asset('backend/assets/css/dashlite.css') }}">
@endif

{{-- toastr --}}
<link rel="stylesheet" href="{{ asset('skeleton/jquery.skeleton.css') }}">
<link rel="stylesheet" href="{{ asset('csv_viewer/css/handsontable.full.css') }}">
<link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
<link rel="stylesheet" href="{{ asset('mini_audio_player/css/jquery.mb.miniAudioPlayer.css') }}">
<link id="skin-default" rel="stylesheet" href="{{ asset('backend/assets/css/theme.css') }}">
@yield('css')

@notifyCss