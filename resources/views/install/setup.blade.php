@extends('install.app')

@section('content')

    <div class="drawer drawer-mobile"><input id="main-menu" type="checkbox" class="drawer-toggle">
        <main class="flex-grow block overflow-x-hidden bg-base-100 text-base-content drawer-content">
            <div id="nav"
                class="inset-x-0 top-0 z-50 w-full transition duration-200 ease-in-out border-b border-base-200 bg-base-100 text-base-content sticky">
                <div class="mx-auto space-x-1 navbar max-w-none">

                    <div class="flex-1"></div>
                    <!---->

                    <div
                        class="navbar w-full col-span-1 shadow-lg xl:col-span-3 bg-neutral-focus text-neutral-content rounded-box">
                        <ul class="w-full steps">
                            <li class="step step-primary">{{ translate('Start') }}</li>
                            <li class="step step-primary">{{ translate('Requirements') }}</li>
                            <li class="step step-primary">{{ translate('Database') }}</li>
                            <li class="step">{{ translate('Migration') }}</li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="p-4 lg:p-10">

                <div class="grid grid-cols-1 gap-6 lg:p-10 xl:grid-cols-0 lg:bg-base-200 rounded-box">

                    <div class="card col-span-1 row-span-3 shadow-lg xl:col-span-2 bg-base-100">
                        <div class="card-body">
                            <h2 class="text-4xl font-bold card-title">{{ translate('Teleman - Telemarketing & Voice Service Application') }}</h2>

                            <form action="{{route('db.setup')}}" method="GET">
                                <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                                    {{ translate('Database Information') }}
                                </h6>
                                <div class="flex flex-wrap">
                                <div class="w-full lg:w-6/12 px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Database Host') }}</span>
                                                </label> 
                                                <input type="hidden" name="types[]" value="DB_HOST">
                                                <input type="text" placeholder="Database Host" name="DB_HOST" class="input" value="localhost" required>
                                            </div>
                                        </div>
                                  </div>
                                </div>
                                <div class="w-full lg:w-6/12 px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Database Port') }}</span>
                                                </label> 
                                                <input type="hidden" name="types[]" value="DB_PORT">
                                                <input type="text" name="DB_PORT" placeholder="Database Port" value="3306" required class="input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full lg:w-6/12 px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Database Name') }}</span>
                                                </label> 
                                                <input type="hidden" name="types[]" value="DB_DATABASE">
                                                <input type="text" name="DB_DATABASE" placeholder="Database Name" value="{{ old('DB_DATABASE') }}" required class="input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full lg:w-6/12 px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Database Username') }}</span>
                                                </label> 
                                                <input type="hidden" name="types[]" value="DB_USERNAME">
                                                <input type="text" name="DB_USERNAME" placeholder="Database Username" value="{{ old('DB_USERNAME') }}" required class="input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Database Password') }}</span>
                                                </label>
                                                <input type="hidden" name="types[]" value="DB_PASSWORD">
                                                <input type="text" name="DB_PASSWORD" placeholder="Database Password" class="input" value="{{ old('DB_PASSWORD') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                    

                            <div class="justify-end space-x-2 card-actions">
                                <button type="submit" class="btn btn-secondary">{{ translate('Go to migration') }}</a>
                            </div>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>
@endsection
