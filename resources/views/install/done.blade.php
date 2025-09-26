@extends('install.app')

@section('content')
        <!-- BEGIN: Congratulations Page -->
        <div class="page  items-center justify-center h-screen text-center">
            <div class="-intro-x lg:mr-20 mb-4">
                <img alt="#Teleman" class="m-auto" src="{{ asset('congo.png') }}">
            </div>
            <div class="text-white lg:mt-10 w-6/12 m-auto mt-12">
                <div class="intro-x text-4xl font-medium">{{ translate('Teleman - Telemarketing & Voice Service Application') }}</div>                
                 <a href="{{route('frontend')}}" class="button w-full inline-block text-xl px-5 py-4 mr-1 mt-8 mb-2 border text-white-700 dark:bg-dark-5 dark:text-white-300">{{ translate('Lets Explore') }}</a>
            </div>
        </div>
        <!-- END: Congratulations Page -->
@endsection
