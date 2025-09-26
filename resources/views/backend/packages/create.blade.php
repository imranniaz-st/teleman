@extends('backend.layouts.master')

@section('title')
{{ translate('Add New Package') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">

            <form action="{{ route('dashboard.packages.store') }}" 
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
                                       value="{{ old('name') }}"
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
                            <span class="form-note">{{ translate('Specify the Package features') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" multiple="multiple"
                                    data-placeholder="Select package features" name="feature_id[]" required="">
                                    @forelse (allFeatures() as $package)
                                        <option value="{{ $package->id }}" {{ old('feature_id') == $package->id ? 'selected' : null }}>
                                            {{ $package->name }}
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
                                    data-placeholder="Select package features" name="twilio_call_costs_id[]" required="">
                                    @forelse (all_teleman_call_costs() as $country)
                                        <option value="{{ $country->id }}" {{ old('twilio_call_costs_id') == $country->id ? 'selected' : null }}>
                                            {{ Str::upper($country->country) }}({{ $country->teleman_cost }})
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
                            <span class="form-note">{{ translate('Specify the package price') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="number" 
                                       class="form-control" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price') }}"
                                       placeholder="Package Price"
                                       required="">
                                       <small>{{ translate('Price is in US dollars') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- limit_restriction --}}
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
                                       value="{{ old('items') }}"
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
                                       value="{{ old('sms') }}"
                                       placeholder="SMS"
                                        required="">
                            </div>
                        </div>
                    </div>
                </div>

                @endif
                {{-- limit_restriction::ENDS --}}

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
                                       value="{{ old('credit') }}"
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
                            <span class="form-note">{{ translate('Specify the URL if your main website is external') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single"
                                    data-placeholder="Select package duration type" name="range_type" required="">
                                    <option value="day" {{ old('range_type') == 'day' ? 'selected' : null }}>{{ translate('Day') }}</option>
                                    <option value="week" {{ old('range_type') == 'week' ? 'selected' : null }}>{{ translate('Week') }}</option>
                                    <option value="month" {{ old('range_type') == 'month' ? 'selected' : null }}>{{ translate('Month') }}</option>
                                    <option value="year" {{ old('range_type') == 'year' ? 'selected' : null }}>{{ translate('Year') }}</option>
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
                                       value="{{ old('range') }}"
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
                                <input type="checkbox" class="custom-control-input" name="active" id="active" value="1">
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
                                <input type="checkbox" class="custom-control-input" name="trial" id="trial" value="1">
                                <label class="custom-control-label" for="trial"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-7 offset-lg-5">
                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Save') }}</button>
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
