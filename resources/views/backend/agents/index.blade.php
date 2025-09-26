@extends('backend.layouts.master')

@section('title')
{{ translate('Agents') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-content-wrap">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <div class="nk-block-des text-soft">
                    <p>{{ translate('You have total') }} {{ all_agents()->count() }} {{ translate('agents') }}.</p>
                </div>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->

    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-toggle="modal" data-target="#modalFormUser">{{ translate('Add New Agent') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="nk-block nk-block-lg mt-2">
        <div class="row g-gs">
            @forelse (all_agents() as $agent)
           
                    <div class="col-sm-6 col-xl-4">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="team">
                                    <div class="team-status bg-{{ $agent->user->restriction == 1 ? 'danger' : 'success' }} text-white">
                                        <em class="icon ni ni-{{ $agent->user->restriction == 1 ? 'na' : 'check-thick' }}" 
                                            title="{{ $agent->user->restriction == 1 ? 'This user is restricted' : 'This user is active' }}"></em>
                                    </div>
                                    <div class="team-options">
                                        <div class="drodown">
                                            <a href="javascript:;" class="dropdown-toggle btn btn-sm btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li>
                                                        <a href="javascript:;"
                                                            data-toggle="modal" data-target="#modalFormAgent{{ $agent->user->id }}">
                                                            <em class="icon ni ni-pen"></em>
                                                            <span>{{ translate('Edit') }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li><a href="{{ route('dashboard.clients.login', [$agent->user->id, Str::slug($agent->user->name)]) }}"><em class="icon ni ni-user"></em><span>{{ translate('Login') }}</span></a></li>
                                                    <li>
                                                        <a href="{{ route('dashboard.agent.change_restriction', [$agent->user->id, Str::slug($agent->user->name)]) }}">
                                                            <em class="icon ni ni-{{ $agent->user->restriction == 1 ? 'unlock' : 'lock-alt' }}"></em>
                                                            <span>{{ $agent->user->restriction == 1 ? 'Unblock' : 'Block' }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('dashboard.agent.destroy', [$agent->user->id, Str::slug($agent->user->name)]) }}">
                                                            <em class="icon ni ni-trash"></em>
                                                            <span>{{ translate('Remove') }}</span>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-card user-card-s2">
                                        <div class="user-avatar lg bg-primary">
                                            <img alt="{{ $agent->user->name }}" 
                                                class="rounded-full"
                                                src="{{ avatar($agent->user->name) }}">
                                        </div>
                                        <div class="user-info">
                                            <h6>{{ $agent->user->name }}</h6>
                                            <span class="sub-text">{{ $agent->user->domain }}</span>
                                        </div>
                                    </div>
                                    <ul class="team-info">
                                        <li><span>{{ translate('Join date') }}</span><span>{{ $agent->user->created_at->format('d M Y') }}</span></li>
                                        <li><span>{{ translate('Email') }}</span><span>{{ $agent->user->email }}</span></li>
                                        <li><span>{{ translate('Phone') }}</span><span>{{ $agent->user->phone }}</span></li>
                                        <li><span>{{ translate('Department') }}</span>
                                            @forelse ($agent->departments as $department)
                                                <span class="badge rounded-pill bg-outline-primary">
                                                    {{ get_department($department->department_id)->name }}
                                                </span>
                                            @empty
                                                
                                            @endforelse    
                                        </li>
                                    </ul>
                                </div><!-- .team -->
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div><!-- .col -->


                    <div class="modal fade" tabindex="-1" id="modalFormAgent{{ $agent->user->id }}">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">{{ $agent->user->name }}</h4>
                                    <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                                        <em class="icon ni ni-cross"></em>
                                    </a>
                                </div>
                                <div class="modal-body modal-body-lg">
                                    <form action="{{ route('dashboard.agent.update', [$agent->user->id, Str::slug($agent->user->name)]) }}" 
                                            class="form-validate is-alter" 
                                            method="POST" 
                                            enctype="multipart/form-data">
                                        @csrf

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">{{ translate('Full Name') }} *</label>
                                                    <span class="form-note">{{ translate('Specify the name of the customer') }}.</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <input type="text" 
                                                                class="form-control" 
                                                                id="name" 
                                                                name="name" 
                                                                value="{{ $agent->user->name }}"
                                                                placeholder="Full Name"
                                                                required="">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row g-3 align-center">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">{{ translate('Email Address') }} *</label>
                                                    <span class="form-note">{{ translate('Specify the email of the customer') }}.</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <input type="email" 
                                                                class="form-control" 
                                                                id="email" 
                                                                name="email" 
                                                                value="{{ $agent->user->email }}"
                                                                placeholder="Email Address"
                                                                required="">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row g-3 align-center">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">{{ translate('Phone') }}</label>
                                                    <span class="form-note">{{ translate('Specify the phone of the customer') }}.</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <input type="text" 
                                                                class="form-control" 
                                                                id="phone" 
                                                                name="phone" 
                                                                value="{{ $agent->user->phone }}"
                                                                placeholder="Phone">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">{{ translate('Password') }} *</label>
                                                    <span class="form-note">{{ translate('Specify the password of the customer') }}.</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-7">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <input type="password" 
                                                                class="form-control" 
                                                                id="password" 
                                                                name="password" 
                                                                value="{{ old('password') }}"
                                                                placeholder="Password">
                                                        <small>{{ translate('Password should be 8 characters') }}</small>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row g-3 align-center">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label class="form-label" for="user_id">{{ translate('Assign Department') }} *</label>
                                                    <span class="form-note">{{ translate('Assing department to the agent') }}.</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-7">
                                                <select class="form-select" multiple="multiple" data-placeholder="{{ translate('Select Department') }}" name="departments[]">
                                                    @foreach (departments() as $department)
                                                        <option value="{{ $department->id }}" {{ agent_is_in_department($agent->id, $department->id) == true ? 'selected' : null }}>{{ Str::upper($department->name) }}   </option>
                                                    @endforeach
                                                </select>
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
                            </div>
                        </div>
                    </div>
         
            @empty
        
            @endforelse
        </div>

    </div><!-- .nk-block -->

</div>

    <!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="modalFormUser">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Add New Agent') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.agent.store') }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Full Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the name of the customer') }}.</span>
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
                                            placeholder="Full Name"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Email Address') }} *</label>
                                <span class="form-note">{{ translate('Specify the email of the customer') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="email" 
                                            class="form-control" 
                                            id="email" 
                                            name="email" 
                                            value="{{ old('email') }}"
                                            placeholder="Email Address"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Phone') }}</label>
                                <span class="form-note">{{ translate('Specify the phone of the customer') }}.</span>
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
                                            placeholder="Phone">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Password') }} *</label>
                                <span class="form-note">{{ translate('Specify the password of the customer') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="password" 
                                            class="form-control" 
                                            id="password" 
                                            name="password" 
                                            value="{{ old('password') }}"
                                            placeholder="Password"
                                            required="">
                                    <small>{{ translate('Password should be 8 characters') }}</small>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="user_id">{{ translate('Assign Department') }} *</label>
                                <span class="form-note">{{ translate('Assing department to the agent') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" multiple="multiple" data-placeholder="{{ translate('Select Department') }}" name="departments[]">
                                @foreach (departments() as $department)
                                    <option value="{{ $department->id }}">{{ Str::upper($department->name) }}   </option>
                                @endforeach
                            </select>
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

<input type="hidden" value="{{ route('check.domain') }}" id="check_domain_url">
<input type="hidden" value="{{ env('YOUR_DOMAIN') }}" id="base_url">

@endsection

@section('js')
    
@endsection
