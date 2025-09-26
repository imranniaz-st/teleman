@extends('backend.layouts.master')

@section('title')
    {{ translate('Voice Campaign') }}
@endsection


@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.campaign.index') }}" class="btn btn-secondary">{{ translate('All Campaigns') }}</a>
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
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PROVIDER') }}</span></th>
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
                        <a href="{{ route('dashboard.campaign.voice.campaign', [$campaign->id, Str::slug($campaign->name)]) }}">
                            {{ $campaign->name }}
                        </a>
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                        {{ provider_name($campaign->provider) }}({{ provider_phone($campaign->provider) }})
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        {{ group_name($campaign->group_id) }}
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
                                                <a href="{{ route('dashboard.campaign.voice.campaign', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-mobile"></em><span>{{ translate('Open Dialer') }}</span>
                                                </a>
                                            </li>
                                        
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr><!-- .nk-tb-item  -->
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
                                <label class="form-label" for="name">{{ translate('Provider Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the name of the campaign name') }}.</span>
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
                                            value="{{ old('xml') }}">
                                    <small>{{ translate('only valid url is applicable') }}</small>
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

                    <div class="row g-3 align-center">
                        <div class="col-lg-12 col-12">
                            <div class="alert alert-fill alert-light alert-icon" role="alert">    
                                <em class="icon ni ni-alert-circle"></em>     
                                <small><strong>{{ translate('Please fillup at least one field from Voice Message Text, Audio Message, Audio File URL. Empty valie will be count as invalid. Audio File take the most priority.') }}</strong></small>
                            </div>
                        </div>
                    </div>
                    
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