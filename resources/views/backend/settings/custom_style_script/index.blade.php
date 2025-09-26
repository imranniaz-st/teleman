@extends('backend.layouts.master')

@section('title')
{{ translate('CUSTOM STYLES & SCRIPTS') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        <form action="{{ route('dashboard.application.custom.styles.scripts.update') }}" class="gy-3 form-validate is-alter" method="POST"
            enctype="multipart/form-data">
            @csrf

            <h4 class="nk-block-title page-title">{{ translate('Frontend') }}</h4>

            <div class="form-group">
                <div class="form-control-wrap">
                    <textarea
                        name="frontend_css" 
                        class="form-control mt-4 form-control-xl form-control-outlined @error('frontend_css') is-invalid @enderror" 
                        id="frontend_css"
                        autocomplete="off">{{ $frontend_css }}</textarea>

                        <label class="form-label-outlined" for="frontend_css">{{ translate('Frontend Custom CSS') }}</label>

                        @error('frontend_css')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                </div>
            </div><!-- .foem-group -->

            <div class="form-group">
                <div class="form-control-wrap">
                    <textarea
                        name="frontend_js" 
                        class="form-control mt-4 form-control-xl form-control-outlined @error('frontend_js') is-invalid @enderror" 
                        id="frontend_js"
                        autocomplete="off">{{ $frontend_js }}</textarea>

                        <label class="form-label-outlined" for="frontend_js">{{ translate('Frontend Custom JS') }}</label>

                        @error('frontend_js')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                </div>
            </div><!-- .foem-group -->

            <h4 class="nk-block-title page-title">{{ translate('Backend') }}</h4>

            <div class="form-group">
                <div class="form-control-wrap">
                    <textarea
                        name="backend_css" 
                        class="form-control mt-4 form-control-xl form-control-outlined @error('backend_css') is-invalid @enderror" 
                        id="backend_css"
                        autocomplete="off">{{ $backend_css }}</textarea>

                        <label class="form-label-outlined" for="backend_css">{{ translate('Backend Custom CSS') }}</label>

                        @error('backend_css')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                </div>
            </div><!-- .foem-group -->

            <div class="form-group">
                <div class="form-control-wrap">
                    <textarea
                        name="backend_js" 
                        class="form-control mt-4 form-control-xl form-control-outlined @error('backend_js') is-invalid @enderror" 
                        id="backend_js"
                        autocomplete="off">{{ $backend_js }}</textarea>

                        <label class="form-label-outlined" for="backend_js">{{ translate('Backend Custom JS') }}</label>

                        @error('backend_js')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                </div>
            </div><!-- .foem-group -->

            <div class="row g-3">
                <div class="col-lg-7 offset-lg-5">
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div><!-- card -->

@endsection

@section('js')

@endsection
