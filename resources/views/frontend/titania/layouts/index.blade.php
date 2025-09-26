@extends('frontend.titania.layouts.master')

@section('title')
    {{ appName() }}
@endsection

@section('css')
    
@endsection

@section('content')
    <!-- BANNER -->
    @includeWhen(true, 'frontend.titania.components.banner')
    <!-- BANNER::END -->

    <!-- features -->
    @includeWhen(true, 'frontend.titania.components.features')
    <!-- features::END -->

    <!-- Process section -->
    @includeWhen(true, 'frontend.titania.components.process')
    {{-- Process::END --}}

    <!-- Covered -->
    @includeWhen(true, 'frontend.titania.components.covered')
    {{-- Covered::END --}}

    {{-- pricing --}}
    @includeWhen(true, 'frontend.titania.components.pricing')
    {{-- pricing --}}

    <!-- Covered -->
    @includeWhen(true, 'frontend.titania.components.newsletter')
    {{-- Covered::END --}}
@endsection

@section('js')
    
@endsection