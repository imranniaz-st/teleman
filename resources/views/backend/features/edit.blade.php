@extends('backend.layouts.master')

@section('title')
{{ translate('Features') }}
@endsection


@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">


    <div class="card card-preview">
        <div class="card-inner">
            <form action="{{ route('dashboard.features.update', [ $feature->id, $feature->slug ]) }}" class="form-validate is-alter" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="name">{{ translate('Feature Name') }} *</label>
                            <span class="form-note">{{ translate('Specify the name of the feature') }}.</span>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $feature->name }}"
                                    placeholder="Feature Name" required="">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="site-off">{{ translate('Active Status') }}</label>
                            <span class="form-note">{{ translate('Enable to make feature active') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="active" id="site-off"
                                    value="1" {{ $feature->active == 1 ? 'checked' : null }}>
                                <label class="custom-control-label" for="site-off"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-7 offset-lg-5">
                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Update') }}</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div><!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')

@endsection
