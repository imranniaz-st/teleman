@extends('backend.layouts.master')

@section('title')
{{ translate('Edit') }} {{ $package->name }} {{ translate('Package') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="row g-3">
    <div class="col-lg-12">
        <div class="form-group">
            <a href="{{ route('dashboard.packages.index') }}" class="btn btn-md btn-secondary"><em class="icon ni ni-curve-up-left mr-2"></em>{{ translate('Go Back To Packages') }}</a>
        </div>
    </div>
</div>

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">

            <form action="{{ route('dashboard.packages.update', [ $package->id, $package->slug ]) }}" 
                  class="gy-3 form-validate is-alter" 
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="site-name">{{ translate('Package Name') }} *</label>
                            <span class="form-note">{{ translate('Specify the name of your website') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" 
                                       class="form-control" 
                                       id="site-name" 
                                       name="name" 
                                       value="{{ $package->name }}"
                                       placeholder="Package Name"
                                        required="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label">{{ translate('Select Package Features') }} *</label>
                            <span class="form-note">{{ translate('Specify the URL if your main website is external') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" multiple="multiple"
                                    data-placeholder="Select package features" name="feature_id[]" required="">
                                    @forelse (allFeatures() as $feature)
                                        <option value="{{ $feature->id }}" 
                                                @foreach(json_decode($package->feature_id) as $feature_id) 
                                                    {{ $feature_id == $feature->id ? 'selected' : '' }}
                                                @endforeach
                                        >
                                            {{ $feature->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label">{{ translate('Select Supported Countries') }} *</label>
                            <span class="form-note">{{ translate('Specify the Package supported countries') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" multiple="multiple"
                                    data-placeholder="Select package supported countries" name="twilio_call_costs_id[]" required="">
                                    @forelse (all_teleman_call_costs() as $country)
                                        <option value="{{ $country->id }}" {{ $package->id == $country->package_id ? 'selected' : '' }}
                                            @foreach($package->supported_countries as $twilio_call_cost) 
                                                    {{ $country->id == $twilio_call_cost->twilio_call_costs_id ? 'selected' : '' }}
                                            @endforeach>
                                            {{ Str::upper($country->country) }} ({{ $country->teleman_cost }})
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="price">{{ translate('Package Price') }} *</label>
                            <span class="form-note">{{ translate('Specify the name of your website') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="number" 
                                       class="form-control" 
                                       id="price" 
                                       name="price" 
                                       value="{{ $package->price }}"
                                       placeholder="Package Price"
                                       required="">
                                       <small>{{ translate('Price is in US dollars') }}</small>
                            </div>
                        </div>
                    </div>
                </div>


                @if (teleman_config('limit_restriction'))
                    
                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="items">{{ translate('Package Emails') }} *</label>
                            <span class="form-note">{{ translate('Specify the package emails') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" 
                                       class="form-control" 
                                       id="items" 
                                       name="emails" 
                                       value="{{ $package->items }}"
                                       placeholder="Package Emails"
                                       required="">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="branch">{{ translate('SMS') }} *</label>
                            <span class="form-note">{{ translate('Specify the package sms') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" 
                                       class="form-control" 
                                       id="branch" 
                                       name="sms" 
                                       value="{{ $package->sms }}"
                                       placeholder="SMS"
                                        required="">
                            </div>
                        </div>
                    </div>
                </div>

                @endif

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="credit">{{ translate('Package Credit') }} *</label>
                            <span class="form-note">{{ translate('Specify the package credit ($)') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" 
                                       class="form-control" 
                                       id="credit" 
                                       name="credit" 
                                       value="{{ $package->credit }}"
                                       placeholder="Package Credit"
                                       required="">
                                       <small>{{ translate('Example') }}: {{ translate('$1 = 10 credits') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label">{{ translate('Package Duration Type') }} *</label>
                            <span class="form-note">{{ translate('Specify the package duration') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select package duration type" name="range_type" required="">
                                    <option value="day" {{ $package->range_type == 'day' ? 'selected' : null }}>{{ translate('Day') }}</option>
                                    <option value="week" {{ $package->range_type == 'week' ? 'selected' : null }}>{{ translate('Week') }}</option>
                                    <option value="month" {{ $package->range_type == 'month' ? 'selected' : null }}>{{ translate('Month') }}</option>
                                    <option value="year" {{ $package->range_type == 'year' ? 'selected' : null }}>{{ translate('Year') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="range">{{ translate('Package Duration') }} *</label>
                            <span class="form-note">{{ translate('Specify the package duration') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" 
                                       class="form-control" 
                                       id="range" 
                                       name="range" 
                                       value="{{ $package->range }}"
                                       placeholder="Package Duration"
                                        required="">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="active">{{ translate('Active Status') }}</label>
                            <span class="form-note">{{ translate('Enable to make website make offline') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="active" id="active" value="1" {{ $package->active == 1 ? 'checked' : null }}>
                                <label class="custom-control-label" for="active"></label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="trial">{{ translate('Available For Trial') }}</label>
                            <span class="form-note">{{ translate('Enable to make website make offline') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="trial" id="trial" value="1" {{ $package->trial == 1 ? 'checked' : null }}>
                                <label class="custom-control-label" for="trial"></label>
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