@extends('backend.layouts.master')

@section('title')
    {{ translate('Groups') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.contact.group.index') }}" class="btn btn-secondary">{{ translate('All Groups') }}</a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <form action="{{ route('dashboard.contact.group.update', [$group->id, Str::slug($group->name)]) }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Group Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the group name') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="name" 
                                            name="name" 
                                            value="{{ $group->name }}"
                                            placeholder="Group Name"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="description">{{ translate('Group description') }} *</label>
                                <span class="form-note">{{ translate('Specify the group description') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <textarea type="text" 
                                            class="form-control" 
                                            id="description" 
                                            name="description" 
                                            placeholder="Group description"
                                            required="">{{ $group->description }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="site-off">{{ translate('Active Status') }}</label>
                                <span class="form-note">{{ translate('Enable to make group active') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                    class="custom-control-input" 
                                    name="status" 
                                    id="site-off" 
                                    {{ $group->status == 1 ? 'checked' : null }}
                                    value="{{ $group->status == 1 ? 1 : 0 }}">
                                    <label class="custom-control-label" for="site-off"></label>
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