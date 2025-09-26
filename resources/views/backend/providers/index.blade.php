@extends('backend.layouts.master')

@section('title')
    {{ translate('Providers') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">

    @can('admin')
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm">{{ translate('Add New Provider') }}</button>
                </li>
                <li class="preview-item">
                    <a href="{{ route('dashboard.provider.export') }}" class="btn btn-secondary"><em class="icon ni ni-download mr-2"></em>{{ translate('Export Providers') }}</a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->
    @endcan

<div class="card card-preview">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                    @can('admin')
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('ASSING TO') }}</span></th>
                    @endcan
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('DEFAULT') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse (getVoiceServerWiseList() as $provider)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                                {{ Str::upper($provider->provider_name) }}
                        </div>
                    </td>
                  
                    @can('admin')
                    <td class="nk-tb-col tb-col-mb">
                        <span class="tb-amount">
                            @if (you($provider->user_id))
                                {{ translate('YOU') }}                                
                            @else
                                {{ Str::upper($provider->user?->name) }}
                            @endif
                        </span>
                    </td>
                    @endcan
                  
                    <td class="nk-tb-col tb-col-mb">
                            <span class="tb-amount">{{ $provider->phone }}</span>
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status text-{{ $provider->status == 1 ? 'success' : 'danger' }}">
                            {{ $provider->status == 1 ? 'Active' : 'Deactive' }}
                        </span>
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        @if (you($provider->user_id))
                                <a href="{{ route('dashboard.provider.set.default', [$provider->id, Str::slug($provider->provider_name)]) }}" 
                                    class="btn-sm btn-{{ $provider->ivr == 1 ? 'info' : 'secondary' }}"
                                    {{ $provider->ivr == 1 ? 'disabled' : null }}>
                                    {{ $provider->ivr == 1 ? 'active' : 'set as default' }}
                                </a>                                
                        @else
                            {{ translate('NO ACCESS') }}
                        @endif
                        
                    </td>
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route('test.initiate_call', [ $provider->id, Str::slug($provider->provider_name) ]) }}"><em class="icon ni ni-call"></em><span>{{ translate('Test Call') }}</span></a></li>
                                            @can('admin')
                                            <li><a href="{{ route('dashboard.provider.edit', [ $provider->id, Str::slug($provider->provider_name) ]) }}"><em class="icon ni ni-pen"></em><span>{{ translate('Edit') }}</span></a></li>
                                            <li><a href="{{ route('dashboard.provider.destroy', [ $provider->id, Str::slug($provider->provider_name) ]) }}"><em class="icon ni ni-trash"></em><span>{{ translate('Trash') }}</span></a></li>
                                            @endcan
                                            
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
</div>
<!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

@can('admin')
    <!-- Modal Form -->
    <div class="modal fade" tabindex="-1" id="modalForm">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ translate('Add New Provider') }}</h4>
                    <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body modal-body-lg">
                    <form action="{{ route('dashboard.provider.store') }}" 
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
                                        <option value="{{ $user->id }}">{{ Str::upper($user->name) }} ({{ $user->email }})</option>
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
                                        value="{{ old('provider_name') }}"
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
                                                value="{{ old('account_sid') }}"
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
                                                value="{{ old('auth_token') }}"
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
                                                value="{{ old('phone') }}"
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
                                    <label class="form-label" for="capability_token">{{ translate('TwiML App SID') }}</label>
                                    <span class="form-note">{{ translate('Specify the TwiML App SID') }}.</span>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" 
                                                class="form-control" 
                                                id="capability_token" 
                                                name="capability_token" 
                                                value="{{ old('capability_token') }}"
                                                placeholder="{{ translate('TwiML App SID') }}">
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
                                                value="{{ old('hourly_quota') }}"
                                                placeholder="Hourly Quota"
                                                required="">
                                        <small>{{ translate('Maximum hourly calling limit. ex: 100') }} </small>
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
@endcan

@endsection

@section('js')
    
@endsection