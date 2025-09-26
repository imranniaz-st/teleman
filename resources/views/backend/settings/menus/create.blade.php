@extends('backend.layouts.master')

@section('title')
    {{ translate('New Menu') }}
@endsection

@section('css')
<link href="{{asset('vendor/harimayco-menu/style.css')}}" rel="stylesheet">
@endsection
    
@section('content')
    {!! Menu::render() !!}
@endsection

@section('js')
    {!! Menu::scripts() !!}
@endsection