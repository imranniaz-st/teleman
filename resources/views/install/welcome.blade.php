@extends('install.app')

@section('content')
    
  <div class="drawer drawer-mobile"><input id="main-menu" type="checkbox" class="drawer-toggle">
        <main class="flex-grow block overflow-x-hidden bg-base-100 text-base-content drawer-content">
            <div id="nav"
                class="inset-x-0 top-0 z-50 w-full transition duration-200 ease-in-out border-b border-base-200 bg-base-100 text-base-content sticky">
                <div class="mx-auto space-x-1 navbar max-w-none">

                    <div class="flex-1"></div>

                    <div class="navbar w-full col-span-1 shadow-lg xl:col-span-3 bg-neutral-focus text-neutral-content rounded-box">
                        <ul class="w-full steps">
                            <li class="step step-primary">{{ translate('Start') }}</li>
                            <li class="step">{{ translate('Requirements') }}</li>
                            <li class="step">{{ translate('Database') }}</li>
                            <li class="step">{{ translate('Migration') }}</li>
                        </ul>
                    </div>
                </div>

            </div>

        <div class="p-4 lg:p-10">
           
                <div class="grid grid-cols-1 gap-6 lg:p-10 xl:grid-cols-0 lg:bg-base-200 rounded-box">


                    <div class="card col-span-1 row-span-3 shadow-lg xl:col-span-2 bg-base-100">
                        <div class="card-body">
                            <h2 class="text-2xl font-bold card-title">{{ translate('Teleman - Telemarketing & Voice Service Application') }}</h2>
                            <p class="my-4 font-bold">{{ translate('You will need to know the following items before proceeding') }}</p>

                            <div class="card shadow-lg compact side bg-base-100 my-3">
                                <div class="flex-row items-center space-x-4 card-body">
                                    <div>
                                        <div class="avatar">
                                                <input type="checkbox" checked="checked" class="checkbox disabled:opacity-50" disabled>
                                                </div>
                                    </div>
                                    <div>
                                        <h2 class="card-title">{{ translate('Database Host Name') }}</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-lg compact side bg-base-100 my-3">
                                <div class="flex-row items-center space-x-4 card-body">
                                    <div>
                                        <div class="avatar">
                                                <input type="checkbox" checked="checked" class="checkbox disabled:opacity-50" disabled>
                                                </div>
                                    </div>
                                    <div>
                                        <h2 class="card-title">{{ translate('Database Name') }}</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-lg compact side bg-base-100 my-3">
                                <div class="flex-row items-center space-x-4 card-body">
                                    <div>
                                        <div class="avatar">
                                                <input type="checkbox" checked="checked" class="checkbox disabled:opacity-50" disabled>
                                                </div>
                                    </div>
                                    <div>
                                        <h2 class="card-title">{{ translate('Database Username') }}</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-lg compact side bg-base-100 my-3">
                                <div class="flex-row items-center space-x-4 card-body">
                                    <div>
                                        <div class="avatar">
                                                <input type="checkbox" checked="checked" class="checkbox disabled:opacity-50" disabled>
                                                </div>
                                    </div>
                                    <div>
                                        <h2 class="card-title">{{ translate('Database Password') }}</h2>
                                    </div>
                                </div>
                            </div>


                            <div class="alert">
                                <div class="flex-1">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#009688" class="flex-shrink-0 w-6 h-6 mx-4 mt-4">     
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>                     
                                    </svg> 

                                    <label>
                                    <p class="text-md text-base-content text-opacity-60">
                                            {{ translate('During the installation process. we will check if the files there needed to be written') }} <strong>(.env file)</strong> {{ translate('have write permission. 
                                            We Will also check if curl are enabled on your server or not. Gather the information mentioned above before hitting the start installation button. if you are ready.') }}
                                        </p>
                                    </label>
                                </div> 
                              
                            </div>


                            <div class="justify-end space-x-2 card-actions">
                                <a href="{{route('permission')}}" class="btn btn-secondary">{{ translate('Start Installation Process') }}</a>
                            </div>
                        </div>
                    </div>

                </div>
        </div>
    </main>
</div>

@endsection