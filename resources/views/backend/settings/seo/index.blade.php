@extends('backend.layouts.master')

@section('title')
{{ translate('SEO') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        <form action="{{ route('dashboard.seo.update') }}" class="gy-3 form-validate is-alter" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-title">{{ translate('Site Title') }}</label>
                        <span class="form-note">{{ translate('Specify the name of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-title" 
                                   name="site_title" 
                                   value="{{ seo('site_title') }}"
                                   required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="customFile">{{ translate('Site Thumbnail') }}</label>
                        <span class="form-note">{{ translate('Specify the thumbnail of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <img src="{{ asset(seo('site_thumbnail')) }}" class="img-fluid mb-3" alt="">
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input" 
                                       name="site_thumbnail" 
                                       id="customFile">
                                <label class="custom-file-label" for="customFile">{{ translate('Choose file') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-description">{{ translate('Site Description') }}</label>
                        <span class="form-note">{{ translate('Specify the site description of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="site-description" name="site_description"
                                value="{{ seo('site_description') }}" required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-keywords">{{ translate('Site Keywords') }}</label>
                        <span class="form-note">{{ translate('Specify the keywords of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" 
                                   class="form-control" 
                                   id="site-keywords" 
                                   name="site_keywords" 
                                   value="{{ seo('site_keywords') }}"
                                required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-author">{{ translate('Site Author') }}</label>
                        <span class="form-note">{{ translate('Specify the author name of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="site-author" name="site_author" value="{{ seo('site_author') }}"
                                required="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Site Copyright') }}</label>
                        <span class="form-note">{{ translate('Copyright information of your website') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="site-copyright" name="site_copyright" value="{{ seo('site_copyright') }}"
                                required="">
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
</div><!-- card -->

@endsection

@section('js')

@endsection
