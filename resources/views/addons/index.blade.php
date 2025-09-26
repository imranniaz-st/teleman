@extends('backend.layouts.master')

@section('title')
{{ translate('Addons') }}
@endsection

@section('css')

@endsection

@section('content')

@can('admin')
    
<div class="card card-preview">
    <div class="card-inner">
        <ul class="preview-list ">
            <li class="preview-item">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addonForm">{{ translate('Add New Addon') }}</button>
            </li>
        </ul>
    </div>
</div><!-- .card-preview -->
@endcan

<div class="col-12">
    <ul class="row g-gs preview-icon-svg">
        @forelse (extended_menu()['addons']['sub_menu'] as $key => $addon)
        
        <li class="col-lg-4 col-sm-6 col-12">
            <div class="preview-icon-box card card-bordered text-center m-auto">
                <div class="preview-icon-wrap text-center">
                    <img src="{{ asset('addons/' . $key . '.png') }}" alt="{{ Str::upper($key) }}" class="m-auto gateway-logo">
                </div>
                <div> 
                    <span class="title text-white fw-bold fs-17px">
                        {{ Str::upper($key) }}
                    </span>
                </div>
                <a class="fw-medium" href="{{ route($addon['route_name']) }}">
                    {{ Str::upper($key) }}
                </a>
            </div>
        </li>

        @empty
            
        @endforelse
       
    </ul>
</div>

@can('admin')
<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="addonForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Add New Addon') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.addons.install') }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                        @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="file" 
                                            class="form-control" 
                                            name="addon_file" 
                                            required>
                                    <small>{{ translate('only .zip file is applicable') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row g-3">
                        <div class="col-lg-7 offset-lg-5">
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Install') }}</button>
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
