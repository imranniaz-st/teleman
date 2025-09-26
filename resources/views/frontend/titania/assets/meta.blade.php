<!-- Required Meta Tags -->
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta name="author" content="{{ seo('site_author') ?? env('AUTHOR') }}">
<meta name="version" content="{{ env('VERSION') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="description" content="{{ seo('site_description') }}"/>
<meta name="keywords" content="{{ seo('site_keywords') }}"/>
<link rel="canonical" href="{{ env('APP_URL') }}" />

<meta property="og:type" content="restaurant website" />
<meta property="og:title" content="@yield('title')" />
<meta property="og:description" content="{{ seo('site_description') }}" />
<meta property="og:image" content="{{ seo('site_thumbnail') }}" />
<meta property="og:url" content="{{ env('APP_URL') }}" />
<meta property="og:site_name" content="{{ application('site_name') }}" />

<meta name="twitter:title" content="@yield('title')">
<meta name="twitter:description" content="{{ seo('site_description') }}">
<meta name="twitter:image" content="{{ seo('site_thumbnail') }}">
<meta name="twitter:site" content="{{ env('APP_URL') }}">
<meta name="twitter:creator" content="{{ seo('site_author') ?? env('AUTHOR') }}">

<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<link rel="icon" href="{{ asset(application('site_favicon')) }}" type="image/gif">