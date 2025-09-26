@extends('backend.layouts.master')

@section('title')
{{ translate('Departments') }}
@endsection


@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm"><em
                            class="icon ni ni-share-alt"></em>
                            <span class="ml-2">
                                {{ translate('Add New Department') }}
                            </span>
                        </button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
        <div class="card-inner">

            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('DEPARTMENT NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('OPTIONS') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse (departments() as $department)

                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                            <div class="user-info">
                                <span class="tb-lead">{{ $department->name }} <span class="dot dot-success d-md-none ml-1"></span></span>
                            </div>
                        </div>
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        @forelse ($department->providers as $provider)
                            <span class="badge badge-dim rounded-pill bg-primary">
                                {{ provider_phone($provider->provider_id) }}
                            </span>
                        @empty
                                
                        @endforelse
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        @forelse (department_options($department->id) as $key => $option)
                            @if ($option == true)
                                @php
                                    $displayKey = $key == 'ivr' ? 'interactive' : $key;
                                    $displayKey = Str::lower(str_replace('_', ' ', $displayKey));
                                @endphp
                                <span class="badge badge-dim rounded-pill bg-primary">
                                    {{ $displayKey }}
                                </span>
                            @endif
                        @empty
                            {{ translate('No Options Available') }}
                        @endforelse
                        
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status text-{{ $department->status == 1 ? 'success' : 'danger' }}">
                            {{ $department->status == 1 ? 'Active' : 'Deactive' }}
                        </span>
                    </td>
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="javascript:;" data-toggle="modal" data-target="#modalForm{{ $department->id }}"><em class="icon ni ni-pen"></em><span>{{ translate('Edit') }}</span></a></li>
                                            <li><a href="{{ route('dashboard.departments.destroy', $department->id) }}"><em class="icon ni ni-trash"></em><span>{{ translate('Trash') }}</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="modal fade" tabindex="-1" id="modalForm{{ $department->id }}">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{ $department->name }}</h4>
                                        <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                                            <em class="icon ni ni-cross"></em>
                                        </a>
                                    </div>
                                    <div class="modal-body modal-body-lg">
                                        <form action="{{ route('dashboard.departments.update', $department->id) }}" class="form-validate is-alter"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">{{ translate('Department Name') }}
                                                            *</label>
                                                        <span
                                                            class="form-note">{{ translate('Specify the department name') }}.</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="name" name="name"
                                                                value="{{ old('name', $department->name) }}" placeholder="Department Name" required="">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row g-3 align-center mt-2">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">{{ translate('Phone Number') }} *</label>
                                                        <span class="form-note">{{ translate('Specify the phone number') }}.</span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <select class="form-select" multiple="multiple" data-placeholder="Select Phone Number" name="provider_id[]" required>
                                                        @foreach(getVoiceServerUserBasedList() as $provider)
                                                                <option value="{{ $provider->id }}" 
                                                                        {{ in_array($provider->id, $department->providers->pluck('provider_id')->toArray()) ? 'selected' : '' }}>
                                                                    {{ $provider->phone }}
                                                                </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="outbound">{{ translate('Outbound') }}</label>
                                                        <span class="form-note">{{ translate('Enable for outbound call') }}.</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="option_status" id="outbound" value="outbound" {{ $department->outbound == true ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="outbound"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="inbound">{{ translate('Inbound') }}</label>
                                                        <span class="form-note">{{ translate('Enable for inbound call') }}.</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="option_status" id="inbound" value="inbound" {{ $department->inbound == true ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="inbound"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="ivr">{{ translate('Interactive Voice') }}</label>
                                                        <span class="form-note">{{ translate('Enable for Interactive Voice.') }}.</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="option_status" id="ivr" value="ivr" {{ $department->ivr == true ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="ivr"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="site-off">{{ translate('Active') }}</label>
                                                        <span class="form-note">{{ translate('Enable to make department active') }}.</span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" name="status" id="site-off" value="1" {{ $department->status == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="site-off"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-lg-7 offset-lg-5">
                                                    <div class="form-group mt-2">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-secondary">{{ translate('Update') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

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

<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Add New Department') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.departments.store') }}" class="form-validate is-alter"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Department Name') }}
                                    *</label>
                                <span
                                    class="form-note">{{ translate('Specify the department name') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Department Name" required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Phone Number') }} *</label>
                                <span class="form-note">{{ translate('Specify the phone number') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" multiple="multiple" data-placeholder="Select Phone Number"
                                name="provider_id[]" required>
                                <option value="">{{ translate('Select Number') }}</option>
                                @foreach(getVoiceServerUserBasedList() as $provider)
                                        <option value="{{ $provider->id }}">
                                            {{ $provider->phone }}
                                        </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="outbound-new">{{ translate('Outbound') }}</label>
                                <span class="form-note">{{ translate('Enable for outbound call') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="option_status" id="outbound-new" value="outbound">
                                    <label class="custom-control-label" for="outbound-new"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="inbound-new">{{ translate('Inbound') }}</label>
                                <span class="form-note">{{ translate('Enable for inbound call') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="option_status" id="inbound-new" value="inbound">
                                    <label class="custom-control-label" for="inbound-new"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Interactive Voice') }}</label>
                                <span class="form-note">{{ translate('Enable for Interactive Voice') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="option_status" id="ivr" value="ivr">
                                    <label class="custom-control-label" for="ivr"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="site-off-new">{{ translate('Active') }}</label>
                                <span class="form-note">{{ translate('Enable to make department active') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="status" id="site-off-new" value="1" checked>
                                    <label class="custom-control-label" for="site-off-new"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-7 offset-lg-5">
                            <div class="form-group mt-2">
                                <button type="submit"
                                    class="btn btn-lg btn-secondary">{{ translate('Save') }}</button>
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
