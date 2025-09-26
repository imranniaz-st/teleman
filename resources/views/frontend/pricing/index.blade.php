@extends('frontend.titania.layouts.master')

@section('title')
    {{ translate('Pricing') }}
@endsection

@section('css')
    
@endsection

@section('content')

<!--Nav-->
@includeWhen(true, 'frontend.titania.components.nav')
<!--Nav::END-->

@includeWhen(true, 'frontend.titania.components.pricing')

@endsection

@section('js')
    
@endsection