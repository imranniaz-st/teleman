@extends('backend.layouts.master')

@section('title')
{{ translate('WordPress WooCommerce Setup') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="card card-bordered">
    <div class="card-inner">

        <form action="{{ route('wp.store') }}" class="gy-3 form-validate is-alter" method="POST"
            enctype="multipart/form-data" autocomplete="off">

            @csrf

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label"
                            for="user_email">{{ translate('WordPress User Email') }}</label>
                        <span
                            class="form-note">{{ translate('Specify the WordPress email') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg form-control-outlined"
                                name="user_email" id="user_email" value="{{ $wordpress->user_email ?? null }}"
                                required="">
                            <label class="form-label-outlined" for="user_email">
                                {{ translate('WordPress User Email') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label"
                            for="application_url">{{ translate('WordPress Installed URL') }}</label>
                        <span
                            class="form-note">{{ translate('Specify the WordPress installed url') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control form-control-lg form-control-outlined"
                                name="application_url" id="application_url"
                                value="{{ $wordpress->application_url ?? null }}" required="">
                            <label class="form-label-outlined" for="application_url">
                                {{ translate('WordPress Installed URL') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-7 offset-lg-5">
                    <div class="form-group mt-2">
                        <button type="submit"
                            class="btn btn-lg btn-secondary">{{ translate('Save Configuration') }}</button>
                    </div>
                </div>
            </div>

        </form>

        @if(isset($wordpress->user_email) && isset($wordpress->application_url))

            <form action="{{ route('wp.generate.token') }}" class="gy-3 form-validate is-alter"
                method="POST" enctype="multipart/form-data" autocomplete="off">

                @csrf

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label"
                                for="user_token">{{ translate('WordPress User Token') }}</label>
                            <span
                                class="form-note">{{ translate('Specify the WordPress user token') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control form-control-lg form-control-outlined"
                                    name="user_token" id="user_token"
                                    value="{{ $wordpress->user_token ?? null }}" required="" disabled>
                                <label class="form-label-outlined" for="user_token">
                                    {{ translate('WordPress User Token') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-7 offset-lg-5">
                        <div class="form-group mt-2">
                            <button type="submit"
                                class="btn btn-lg btn-secondary">{{ translate('Generate Token') }}</button>
                        </div>
                    </div>
                </div>

            </form>

        @endif

    </div>
</div><!-- card -->

<div class="nk-block nk-block-lg">

    <div class="nk-block nk-block-lg">
        <div class="card card-preview mt-4">
            <div class="card-inner">
                <ul class="preview-list ">
                    <li class="preview-item">
                        <a href="{{ route('wp.fetch.data') }}" class="btn btn-secondary"><em
                                class="icon ni ni-download mr-2"></em>{{ translate('Fetch Contacts From WooCommerce') }}</a>
                    </li>

                    @if (session()->has('woocommerce') && session('woocommerce')->count() > 0)
                    <li class="preview-item">
                        <a href="{{ route('wp.fetch.store') }}" class="btn btn-secondary"><em
                                class="icon ni ni-download mr-2"></em>{{ translate('Store Contacts') }}</a>
                    </li>
                    @endif

                </ul>
            </div>
        </div><!-- .card-preview -->

        @if (session()->has('woocommerce'))
            <div class="card card-preview mt-4">
                <div class="card-inner">
                    <ul class="preview-list ">
                        <li class="preview-item">
                            <p>{{ translate('Total') }} <strong>{{ session('woocommerce')->count() }}</strong> 
                                {{ Str::plural('contacts', session('woocommerce')->count()) }} {{ translate('found') }}</p>
                        </li>
                    </ul>
                </div>
            </div><!-- .card-preview -->
        @endif

        <div class="card card-preview">
            <div class="card-inner">
                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                    <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NUMBER') }}</span></th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if (session()->has('woocommerce'))
                            @forelse (session('woocommerce') as $woocommerce)
                                <tr class="nk-tb-item">
                                    <td class="nk-tb-col">
                                        <div class="user-card">
                                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                                <span>{{ $loop->iteration }}</span>
                                            </div>
                                                {{ Str::upper($woocommerce->name) }}
                                        </div>

                                    </td>
                                
                                    <td class="nk-tb-col tb-col-mb">
                                        <span class="tb-amount">{{ $woocommerce->phonenumber ?? null }}</span>
                                    </td>
                                
                                </tr><!-- .nk-tb-item  -->
                            @empty
                                    
                            @endforelse
                        @endif
                
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- END: Large Slide Over Toggle -->

</div>

@endsection

@section('js')

@endsection
