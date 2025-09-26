<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="windows desktop landscape">

<head>
    <title>@yield('title')</title>
    {{-- meta --}}
    @includeWhen(true, 'frontend.titania.assets.meta')
    <!--Core CSS -->
    @includeWhen(true, 'frontend.titania.assets.css')
    @yield('css')
</head>

<body class="is-theme-green">
    {{-- PRELOADER --}}
    @includeWhen(true, 'frontend.titania.components.preloader')
    {{-- PRELOADER::END --}}
    
    @yield('content')

    <!-- Side footer -->
    @includeWhen(true, 'frontend.titania.components.footer')
    <!-- /Side footer -->

    <!-- Back To Top Button -->
    <div id="backtotop"><a href="javascript:;"></a></div>
   
    <!-- /JS -->
    @includeWhen(true, 'frontend.titania.assets.js')
    @includeWhen(true, 'frontend.titania.components.editor')

    @yield('js')
</body>

</html>
