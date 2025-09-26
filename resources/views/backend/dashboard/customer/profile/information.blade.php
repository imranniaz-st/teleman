@extends('backend.layouts.master')

@section('title')
{{ translate('Personal Information') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-content-wrap">

    <ul class="nk-nav nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.profile.information') }}">{{ translate('Personal') }}</a>
        </li>

        @can('customer')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.profile.billing') }}">{{ translate('Billing') }}</a>
            </li>
        @endcan

    </ul><!-- nav-tabs -->
    <div class="nk-block">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h5 class="nk-block-title">{{ translate('Personal Information') }}</h5>
                <div class="nk-block-des">
                    <p>{{ translate('Basic info, like your name and address, that you use on') }} {{ orgName() }} {{ translate('Platform') }}.</p>
                </div>
            </div>
        </div><!-- .nk-block-head -->
        <div class="card card-bordered">
            <div class="nk-data data-list">
                <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                    <div class="data-col">
                        <span class="data-label">{{ translate('Full Name') }}</span>
                        <span class="data-value">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more"><em
                                class="icon ni ni-forward-ios"></em></span></div>
                </div><!-- .data-item -->
                <div class="data-item">
                    <div class="data-col">
                        <span class="data-label">{{ translate('Email') }}</span>
                        <span class="data-value">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more disable"><em
                                class="icon ni ni-lock-alt"></em></span></div>
                </div><!-- .data-item -->
                <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                    <div class="data-col">
                        <span class="data-label">{{ translate('Phone Number') }}</span>
                        <span class="data-value text-soft">{{ Auth::user()->phone }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more"><em
                                class="icon ni ni-forward-ios"></em></span></div>
                </div><!-- .data-item -->

            </div><!-- .nk-data -->
        </div><!-- .card -->


        @can('customer')

        <!-- Another Section -->
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h5 class="nk-block-title">{{ translate('Company Information') }}</h5>
                <div class="nk-block-des">
                    <p>{{ translate('Your Company information here') }}.</p>
                </div>
            </div>
        </div><!-- .nk-block-head -->
        <div class="card card-bordered">
            <div class="nk-data data-list">

                <div class="data-item">
                    <div class="data-col">
                        <span class="data-label">{{ translate('Company Name') }}</span>
                        <span class="data-value">{{ Auth::user()->rest_name }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more disable"><em
                                class="icon ni ni-lock-alt"></em></span></div>
                </div><!-- .data-item -->

                <div class="data-item">
                    <div class="data-col">
                        <span class="data-label">{{ translate('Company Address') }}</span>
                        <span class="data-value">{{ Auth::user()->rest_address }}</span>
                    </div>
                    <div class="data-col data-col-end"><span class="data-more disable"><em
                                class="icon ni ni-lock-alt"></em></span></div>
                </div><!-- .data-item -->

            </div><!-- .nk-data -->
        </div><!-- .card -->

        @endcan

    </div><!-- .nk-block -->
</div>




<div class="modal fade" tabindex="-1" role="dialog" id="profile-edit">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="javascript:;" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-lg">
                <h5 class="title">{{ translate('Update Profile') }}</h5>
                <ul class="nk-nav nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#personal">{{ translate('Personal') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#password">{{ translate('Change Password') }}</a>
                    </li>
                </ul><!-- .nav-tabs -->
                <div class="tab-content">

                    <div class="tab-pane active" id="personal">
                        <form action="{{route('dashboard.profile.update')}}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="full-name">{{ translate('Full Name') }}</label>
                                        <input type="text" class="form-control form-control-lg" id="full-name"
                                            value="{{Auth::user()->name}}" name="name" placeholder="Enter Full name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="phone-no">{{ translate('Phone Number') }}</label>
                                        <input type="text" class="form-control form-control-lg" id="phone-no"
                                            value="{{Auth::user()->phone}}" name="phone" placeholder="Phone Number">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Update Profile') }}</button>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-dismiss="modal" class="link link-light">{{ translate('Cancel') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="password">
                        <form action="{{route('dashboard.profile.updatePassword')}}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="old-password">{{ translate('Old Password') }}</label>
                                        <input type="password" class="form-control form-control-lg" id="old-password"
                                            name="oldpassword" placeholder="Old Password">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="new-password">{{ translate('New Password') }}</label>
                                        <input type="password" class="form-control form-control-lg" id="new-password"
                                            name="newpassword" placeholder="New Password">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="confirm-password">{{ translate('Confirm Password') }}</label>
                                        <input type="password" class="form-control form-control-lg" id="confirm-password"
                                            name="confirmpassword" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Update Password') }}</button>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-dismiss="modal" class="link link-light">{{ translate('Cancel') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- .tab-pane -->

            </div><!-- .tab-content -->
        </div><!-- .modal-body -->
    </div><!-- .modal-content -->
</div><!-- .modal-dialog -->

@endsection

@section('js')

@endsection
