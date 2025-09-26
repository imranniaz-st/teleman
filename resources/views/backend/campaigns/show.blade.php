@extends('backend.layouts.master')

@section('title')
    {{ translate('Audio Campaign') }}
@endsection


@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.campaign.index') }}" 
                    class="btn btn-secondary">{{ translate('All Campaigns') }}</a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <form action="{{ route('dashboard.campaign.update', [$campaign->id, Str::slug($campaign->name)]) }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Campaign Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the campaign name') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="name" 
                                            name="name" 
                                            value="{{ $campaign->name }}"
                                            placeholder="Campaign Name"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Provider Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the name of the campaign name') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select Provider" name="provider">
                                @foreach (all_providers() as $provider)
                                    <option value="{{ $provider->id }}" {{ $campaign->provider == $provider->id ? 'selected' : null }}>{{ Str::upper($provider->provider_name) }} ({{ $provider->phone }})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Assign To Group') }}</label>
                                <span class="form-note">{{ translate('Specify the group of the campaign') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" 
                                    single="single" 
                                    data-placeholder="Select Groups" 
                                    name="group_id">
                                @foreach (allGroups() as $group)
                                    <option value="{{ $group->id }}" {{ $campaign->group_id == $group->id ? 'selected' : null }}>{{ Str::upper($group->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
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
                                            value="{{ $campaign->say ?? null }}"></textarea>
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
                                            value="{{ old('audio') }}">
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
                                            value="{{ $campaign->xml ?? null }}">
                                    <small>{{ translate('only valid url is applicable') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="expectation">{{ translate('Target Expectation') }}</label>
                                <span class="form-note">{{ translate('Specify the target leads expectation') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="expectation" 
                                            name="expectation" 
                                            placeholder="80"
                                            value="{{ $campaign->expectation ?? null }}">
                                    <small>{{ translate('The leads target will be present in percentage(%)') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="description">{{ translate('Description') }}</label>
                                <span class="form-note">{{ translate('Specify the description') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <textarea type="text" 
                                            class="form-control" 
                                            id="description" 
                                            name="description" required>{{ $campaign->description ?? null }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row g-3 align-center">
                        <div class="col-lg-12 col-12">
                            <div class="alert alert-fill alert-light alert-icon" role="alert">    
                                <em class="icon ni ni-alert-circle"></em>     
                                <small><strong>{{ translate('Please fillup at least one field from Voice Message Text, Audio Message, Audio File URL. Empty valie will be count as invalid. Audio File take the most priority.') }}</strong></small>
                            </div>
                        </div>
                    </div> --}}
                    
                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="site-off">{{ translate('Active Status') }}</label>
                                <span class="form-note">{{ translate('Enable to make campaign active') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="status" id="site-off" value="{{ $campaign->status == 1 ? 1 : 0 }}" {{ $campaign->status == 1 ? 'checked' : null }}>
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