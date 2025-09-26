@extends('backend.layouts.master')

@section('title')
    {{ translate('Update Provider') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.provider.index') }}" 
                    class="btn btn-secondary">{{ translate('All Providers') }}</a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <form action="{{ route('dashboard.provider.update', [$provider->id, Str::slug($provider->provider_name)]) }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="user_id">{{ translate('Assign To') }} *</label>
                                <span class="form-note">{{ translate('Assing the provider to an user') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select User" name="user_id">
                                @foreach (all_users() as $user)
                                    <option value="{{ $user->id }}" {{ $provider->user_id == $user->id ? 'selected' : null }}>{{ Str::upper($user->name) }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Provider Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the name of the provider name') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <input type="text" 
                                    class="form-control" 
                                    id="name" 
                                    name="provider_name" 
                                    value="{{ old('provider_name', $provider->provider_name) }}"
                                    placeholder="Provider Name"
                                    required="">
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="account_sid">{{ translate('Account SID/Key') }} *</label>
                                <span class="form-note">{{ translate('Specify the account key/sid') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="account_sid" 
                                            name="account_sid" 
                                            value="{{ $provider->account_sid }}"
                                            placeholder="Account SID/Key"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="auth_token">{{ translate('Auth Token/Secret Key') }} *</label>
                                <span class="form-note">{{ translate('Specify the auth token/secret key') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="auth_token" 
                                            name="auth_token" 
                                            value="{{ $provider->auth_token }}"
                                            placeholder="Auth Token or Secret Key"
                                            required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="phone">{{ translate('Phone Number') }} *</label>
                                <span class="form-note">{{ translate('Specify the phone number') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="phone" 
                                            name="phone" 
                                            value="{{ $provider->phone }}"
                                            placeholder="Phone Number"
                                            required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    {{-- DEPREACTED::STARTS --}}
                    {{-- <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="say">{{ translate('Voice Message Text') }}</label>
                                <span class="form-note">{{ translate('Specify the voice message text') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <textarea type="text" 
                                            class="form-control" 
                                            id="say" 
                                            name="say" 
                                            value="{{ $provider->say }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="audio">{{ translate('Pre-recorded Audio Message') }}</label>
                                <span class="form-note">{{ translate('Specify the pre-recorded audio file') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="file" 
                                            class="form-control" 
                                            id="audio" 
                                            name="audio" 
                                            value="{{ $provider->audio }}">
                                    <small>{{ translate('only .mp3 file is applicable') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="xml">{{ translate('Audio File URL') }}</label>
                                <span class="form-note">{{ translate('Specify the audio file url') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="xml" 
                                            name="xml" 
                                            value="{{ $provider->xml }}">
                                    <small>{{ translate('only valid url is applicable') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-12 col-12">
                            <div class="alert alert-fill alert-light alert-icon" role="alert">    
                                <em class="icon ni ni-alert-circle"></em>     
                                <small><strong>{{ translate('Please fillup at least one field from Voice Message Text, Audio Message, Audio File URL. Empty valie will be count as invalid. Audio File take the most priority.') }}</strong></small>
                            </div>
                        </div>
                    </div> --}}
                    {{-- DEPREACTED::ENDS --}}

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="capability_token">{{ translate('TwiML App SID Token') }} *</label>
                                <span class="form-note">{{ translate('Specify the TwilML App SID token') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="capability_token" 
                                            name="capability_token" 
                                            value="{{ $provider->capability_token }}"
                                            placeholder="{{ translate('TwiML App SID') }}"
                                            required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="hourly_quota">{{ translate('Hourly Quota') }} *</label>
                                <span class="form-note">{{ translate('Specify the hourly quota') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="hourly_quota" 
                                            name="hourly_quota" 
                                            value="{{ $provider->hourly_quota }}"
                                            placeholder="Hourly Quota"
                                            required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="site-off">{{ translate('Active Status') }}</label>
                                <span class="form-note">{{ translate('Enable to make provider active') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="status" id="site-off" value="1" {{ $provider->status == 1 ? 'checked' : null }}>
                                    <label class="custom-control-label" for="site-off"></label>
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