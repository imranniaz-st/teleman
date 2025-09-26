@extends('install.app')

@section('content')

    <div class="drawer drawer-mobile"><input id="main-menu" type="checkbox" class="drawer-toggle">
        <main class="flex-grow block overflow-x-hidden bg-base-100 text-base-content drawer-content">

            <div class="p-4 lg:p-10">

                <div class="grid grid-cols-1 gap-6 lg:p-10 xl:grid-cols-2 lg:bg-base-200 rounded-box">

                    <div class="card col-span-1 row-span-3 shadow-lg xl:col-span-2 bg-base-100">
                        <div class="card-body">
                            <h2 class="text-4xl font-bold card-title text-center">{{ translate('Teleman - Telemarketing & Voice Service Application') }}</h2>

                            <form action="{{route('admin.store')}}" method="POST">
                                @csrf

                            <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                                {{ translate('Admin Information') }}
                            </h6>

                            <div class="flex flex-wrap">
                                <div class="w-full lg:w-6/12 px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Full Name') }}</span>
                                                </label> 
                                                <input type="text" placeholder="Full Name" name="name" class="input" value="" required>
                                            </div>
                                        </div>
                                  </div>
                                </div>

                                <div class="w-full lg:w-6/12 px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Email') }}</span>
                                                </label> 
                                                <input type="email" name="email" placeholder="Enter Email Address" value="" required class="input">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full px-4">
                                    <div class="relative w-full mb-3">
                                        <div class="p-10 card bg-base-200">
                                            <div class="form-control">
                                                <label class="label">
                                                <span class="label-text">{{ translate('Password') }}</span>
                                                </label>
                                                <input type="password" name="password" placeholder="Enter Password" class="input" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="justify-end space-x-2 card-actions">
                                <button type="submit" class="btn btn-secondary">{{ translate('Submit') }}</a>
                            </div>

                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>


@endsection

@section('script')

<script src="{{ filePath('assets/js/jquery.js') }}"></script>
<script src="{{ filePath('assets/js/parsley.js') }}"></script>
<script src="{{ filePath('assets/js/validation.js') }}"></script>

@endsection

