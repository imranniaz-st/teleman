@extends('backend.layouts.master')

@section('title')
    {{ translate('Voice Mail Campaign') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm">{{ translate('Create New Campaign') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NUMBER') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('GROUP') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('LAST SERVED') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse (campaigns() as $campaign)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <span>{{ $loop->iteration }}</span>
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                        {{ $campaign->name }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                        {{ provider_phone($campaign->provider) }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-md" title="{{ count(campaign_emails($campaign->id)) }} {{ Str::plural('contacts', count(campaign_emails($campaign->id))) }}">
                        {{ group_name($campaign->group_id) }}({{ count(campaign_emails($campaign->id)) }})
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        {{ $campaign->created_at->diffForHumans() }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status text-{{ $campaign->status == 1 ? 'success' : 'danger' }}">
                            {{ $campaign->status == 1 ? 'Active' : 'Deactive' }}
                        </span>
                    </td>
                    
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li>
                                                <a href="{{ route('dashboard.campaign.start.campaign', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-vol"></em><span>{{ translate('Send Voice Mail') }}</span>
                                                </a>
                                            </li>

                                            @if (env('SMS_MARKETING') == 'YES')
                                            
                                            <li>
                                                <a href="javascript:;" data-toggle="modal" data-target="#smsForm">
                                                    <em class="icon ni ni-chat"></em><span>{{ translate('Send SMS') }}</span>
                                                </a>
                                            </li>

                                            @endif

                                            {{-- @if ($campaign->ivr)
                                            <li>
                                                <a href="{{ route('dashboard.ivr.calling', [$campaign->id, Auth::id(), Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-mic"></em><span>{{ translate('IVR Call') }}</span>
                                                </a>
                                            </li>
                                            @endif --}}
                                            
                                            
                                            @can('dev')
                                             <li>
                                                <a href="{{ route('dashboard.campaign.make.dev.call', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-headphone"></em><span>{{ translate('Test Call') }}</span>
                                                </a>
                                            </li>
                                            @endcan

                                            <li>
                                                <a href="{{ route('dashboard.campaign.edit', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-pen"></em><span>{{ translate('Edit') }}</span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('dashboard.campaign.destroy', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-trash"></em><span>{{ translate('Remove') }}</span>
                                                </a>
                                            </li>

                                            @can('admin')
                                                
                                            @if ($campaign->provider)
                                            
                                            <li>
                                                <a href="{{ route('dashboard.cron.jobs.destroy', [$campaign->id, $campaign->group_id, $campaign->provider, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-clock"></em><span>{{ translate('Stop Cron Job') }}</span>
                                                </a>
                                            </li> 
                                            
                                            @endif
                                            @endcan
                                        
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr><!-- .nk-tb-item  -->


                @if (env('SMS_MARKETING') == 'YES')
                <div class="modal fade" tabindex="-1" id="smsForm">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title lead">{{ translate('Message Content For ') . $campaign->name }}</h4>
                                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                                    <em class="icon ni ni-cross"></em>
                                </a>
                            </div>
                            <div class="modal-body modal-body-lg">
                                <form action="{{ route('dashboard.campaign.start.sms', [$campaign->id, Str::slug($campaign->name)]) }}" 
                                        class="form-validate is-alter" 
                                        method="POST" 
                                        enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-4">
                                        {{ translate('ShortCodes') }}: {name} {phone} {country} {profession}
                                    </div>

                                    <div class="row g-3 align-center">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <label class="form-label" for="description">{{ translate('Write your message content here') }}</label>
                                                    <textarea type="text" 
                                                            class="form-control" 
                                                            id="content" 
                                                            name="content"
                                                            required>{{ old('content', $campaign->sms_content->content ?? null) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 align-center mt-4">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label" for="mojsms">{{ translate('MojSMS') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="third_party_provider" id="mojsms" value="mojsms">
                                                    <label class="custom-control-label" for="mojsms"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 align-center d-none">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label" for="telnyx">{{ translate('Telnyx') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" name="third_party_provider" id="telnyx" value="telnyx">
                                                    <label class="custom-control-label" for="telnyx"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-lg-7 offset-lg-5">
                                            <div class="form-group mt-2">
                                                <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Send SMS') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif


            @empty
                    
            @endforelse
           
            </tbody>
        </table>
    </div>
</div><!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Create New Campaign') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.campaign.store') }}" 
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
                                            value="{{ old('name') }}"
                                            placeholder="Campaign Name"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Provider') }} *</label>
                                <span class="form-note">{{ translate('Specify the provider') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select Provider" name="provider">
                                @foreach (all_providers() as $provider)
                                    <option value="{{ $provider->id }}">{{ Str::upper($provider->provider_name) }} ({{ $provider->phone }})</option>
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
                                    <option value="{{ $group->id }}">{{ Str::upper($group->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    @if (teleman_config('hide'))
                        
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
                                            value="{{ old('say') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

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

                    @if (teleman_config('hide'))
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
                                                value="{{ old('xml') }}">
                                        <small>{{ translate('only valid url is applicable') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                            value="{{ old('expectation') }}">
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
                                            name="description" 
                                            value="{{ old('description') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row g-3 align-center mt-2 mb-2">
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
                                    <input type="checkbox" class="custom-control-input" name="status" id="site-off" value="1">
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
        </div>
    </div>
</div>
    
@endsection

@section('js')
    
@endsection