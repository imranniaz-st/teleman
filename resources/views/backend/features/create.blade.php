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
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm">{{ translate('Add New Feature') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('FEATURE NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse (allFeaturesPaginate() as $feature)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                            <div class="user-info">
                                <span class="tb-lead">{{ $feature->name }} <span class="dot dot-success d-md-none ml-1"></span></span>
                            </div>
                        </div>
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status text-{{ $feature->active == 1 ? 'success' : 'danger' }}">
                            {{ $feature->active == 1 ? 'Active' : 'Deactive' }}
                        </span>
                    </td>
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route('dashboard.features.edit', [ $feature->id, $feature->slug ]) }}"><em class="icon ni ni-pen"></em><span>{{ translate('Edit') }}</span></a></li>
                                            <li><a href="{{ route('dashboard.features.destroy', [ $feature->id, $feature->slug ]) }}"><em class="icon ni ni-trash"></em><span>{{ translate('Trash') }}</span></a></li>
                                            <li><a href="{{ route('dashboard.features.status', [ $feature->id, $feature->slug ]) }}"><em class="icon ni ni-focus"></em><span>{{ $feature->active == 1 ? 'Deactive' : 'Active' }}</span></a></li>
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
                <h4 class="modal-title">{{ translate('Add New Feature') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.features.store') }}" 
                        class="form-validate is-alter" 
                        method="POST" 
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
                                    <input type="text" 
                                            class="form-control" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name') }}"
                                            placeholder="Feature Name"
                                            required="">
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
                                    <input type="checkbox" class="custom-control-input" name="active" id="site-off" value="1">
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