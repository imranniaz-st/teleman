@extends('install.app')

@section('content')

    <div class="drawer drawer-mobile"><input id="main-menu" type="checkbox" class="drawer-toggle">
        <main class="flex-grow block overflow-x-hidden bg-base-100 text-base-content drawer-content">
            <div id="nav"
                class="inset-x-0 top-0 z-50 w-full transition duration-200 ease-in-out border-b border-base-200 bg-base-100 text-base-content sticky">
                <div class="mx-auto space-x-1 navbar max-w-none">

                    <div class="flex-1"></div>

                    <div
                        class="navbar w-full col-span-1 shadow-lg xl:col-span-3 bg-neutral-focus text-neutral-content rounded-box">
                        <ul class="w-full steps">
                            <li class="step step-primary">{{ translate('Start') }}</li>
                            <li class="step step-primary">{{ translate('Check Requirements') }}</li>
                            <li class="step step-primary">{{ translate('Database Setup') }}</li>
                            <li class="step step-primary">{{ translate('Migration') }}</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="p-4 lg:p-10">

                <div class="grid grid-cols-1 gap-6 lg:p-10 xl:grid-cols-0 lg:bg-base-200 rounded-box">

                    <div class="card col-span-1 row-span-3 shadow-lg xl:col-span-2 bg-base-100">
                        <div class="card-body">
                            <h2 class="text-2xl font-bold card-title">{{ translate('Teleman - Telemarketing & Voice Service Application') }}</h2>

                        @if($message = Session::get('success'))

                            <div class="hero h-full bg-base-200 rounded-box">
                                <div class="text-center hero-content">
                                    <div class="max-w-md">
                                        <h1 class="mb-5 text-2xl font-bold">
                                            {{ translate('Select Your Migration Type') }}
                                        </h1> 
                                         
                                        <a href="{{ route('import.fresh.data') }}" class="btn btn-outline">{{ translate('Fresh Content') }}</a>
                                    
                                        <div class="-m-2 text-center mt-5">
                                            <div class="p-2">
                                                <div class="inline-flex items-center bg-white leading-none text-black rounded-full p-2 shadow text-sm">
                                                <span class="inline-flex bg-blue-600 text-white rounded-full h-6 px-3 justify-center items-center">{{ translate('Fresh') }}</span>
                                                <span class="inline-flex px-2">{{ translate('Configure everything from scratch') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                    
                                </div>

                            </div>

                        @endif

                        @if($message = Session::get('wrong'))

                            <h2 class="text-center">{{ translate('Check the Database connection') }}</h2>
                            <a href="{{route('create')}}" class="btn btn-outline">{{ translate('Go to the Database Setup') }}</a>

                        @endif

                    </div>

                </div>
            </div>
        </main>
    </div>

@endsection