<link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/'. active_theme() .'/assets/css/app.css') }}">
<link id="theme-sheet" rel="stylesheet" href="{{ asset('frontend/'. active_theme() .'/assets/css/green.css') }}">


<script src="{{ asset('js/hyper.js') }}"></script>

@auth
    @can('admin')
        <link rel="stylesheet" href="{{ asset('css/editor.css') }}" />
    @endcan
@endauth

@notifyCss

<style>
.pageloader {
    background-color: {{ orgColor() }} !important;
}

/* @php
    echo custom_css_script('frontend_css');
@endphp */

</style>