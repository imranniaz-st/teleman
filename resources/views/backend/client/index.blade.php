@extends('backend.layouts.master')

@section('title')
{{ translate('All Clients') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-content-wrap">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <div class="nk-block-des text-soft">
                    <p>{{ translate('You have total') }} {{ allClientsCount() }} {{ translate('clients') }}.</p>
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
                            data-toggle="modal" data-target="#modalFormUser">{{ translate('Add New User') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="nk-block nk-block-lg mt-2">
        <div class="row g-gs">
            @forelse (allClients() as $client)
                @if ($client->item_limit_count != null)
                    <div class="col-sm-6 col-xl-4">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="team">
                                    <div class="team-status bg-{{ $client->restriction == 1 ? 'danger' : 'success' }} text-white">
                                        <em class="icon ni ni-{{ $client->restriction == 1 ? 'na' : 'check-thick' }}" 
                                            title="{{ $client->restriction == 1 ? 'This user is restricted' : 'This user is active' }}"></em>
                                    </div>
                                    
                                    <div class="team-options">
                                        <div class="drodown">
                                            <a href="javascript:;" class="dropdown-toggle btn btn-sm btn-icon btn-trigger"
                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="{{ route('dashboard.clients.send.expiry.alert', $client->domain) }}"><em class="icon ni ni-mail"></em><span>{{ translate('Send Expiry Alert') }}</span></a></li>
                                                    <li><a href="{{ route('dashboard.clients.limit.manager', [$client->id, Str::slug($client->name)]) }}"><em class="icon ni ni-shield-star"></em><span>{{ translate('Limit Manager') }}</span></a></li>
                                                    <li><a href="{{ route('dashboard.clients.login', [$client->id, Str::slug($client->name)]) }}"><em class="icon ni ni-user"></em><span>{{ translate('Login') }}</span></a></li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a href="{{ route('dashboard.clients.restriction', [$client->id, Str::slug($client->name)]) }}">
                                                            <em class="icon ni ni-{{ $client->restriction == 1 ? 'unlock' : 'lock-alt' }}"></em>
                                                            <span>{{ $client->restriction == 1 ? translate('Unblock') : translate('Block') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('dashboard.clients.subscribe', [$client->id, Str::slug($client->name)]) }}">
                                                            <em class="icon ni ni-{{ $client->subscription->active == 1 ? 'unlock' : 'lock-alt' }}"></em>
                                                            <span>{{ $client->subscription->active == 1 ? 'Unsubscribe' : 'Subscribe' }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('dashboard.clients.destroy', [$client->id, $client->domain]) }}"
                                                            onclick="return confirm('you want to delete?');">
                                                            <em class="icon ni ni-trash-alt"></em>
                                                            <span>{{ translate('Trash') }}</span> 
                                                            <em class="icon ni ni-{{ checkExpiry($client->id) == "EXPIRED" && userRestriction($client->id) == "true" ? 'check-thick text-success' : 'na text-danger' }} f-12 ml-1" 
                                                                title="{{ translate('Subscribed/Active user cannot be deleted') }}">
                                                            </em>
                                                        </a>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-card user-card-s2">
                                        <div class="user-avatar lg bg-primary">
                                            <img alt="{{ $client->name }}" 
                                                class="rounded-full"
                                                src="{{ avatar($client->name) }}">
                                        </div>
                                        <div class="user-info">
                                            <h6>{{ $client->name }}</h6>
                                            <span class="sub-text">{{ $client->domain }}</span>
                                            <span class="badge text-white bg-{{ $client->subscription->active == 0 ? 'danger' : 'success' }}">{{ $client->subscription->active == 0 ? 'not subscribed' : 'subscribed' }}</span>
                                            <span class="badge text-white bg-{{ checkExpiry($client->id) == 'EXPIRED' ? 'danger' : null }}">{{ checkExpiry($client->id) == 'EXPIRED' ? 'expired' : null }}</span>
                                            <span class="badge text-white bg-{{ kyc_verified($client->id) == true ? 'secondary' : null }}">{{ kyc_verified($client->id) == true ? 'verified' : null }}</span>
                                        </div>
                                    </div>
                                    <ul class="team-info">
                                        <li><span>{{ translate('Join date') }}</span><span>{{ $client->created_at->format('d M Y') }}</span></li>
                                        <li><span>{{ translate('Contact') }}</span><span>{{ $client->phone }}</span></li>
                                        <li><span>{{ translate('Email') }}</span><span>{{ $client->email }}</span></li>
                                        <li><span>{{ translate('Company name') }}</span><span>{{ Str::limit($client->rest_name, 20) }}</span></li>
                                        <li><span>{{ translate('Plan start') }}</span><span>{{ $client->subscription->start_at }}</span></li>
                                        <li><span>{{ translate('Plan end') }}</span><span>{{ $client->subscription->end_at }}</span></li>
                                        <li><span>{{ translate('Credits') }}</span><span>{{ price(user_current_credit($client->id)) }}</span></li>
                                        <li><span>{{ translate('Expired in') }}</span><span>{{ convertdaysToWeeksMonthsYears(userSubscriptionDateEndIn($client->domain)) }}</span></li>
                                    </ul>
                                    <div class="team-view">
                                        <a href="{{ route('dashboard.profile.account.report', [$client->domain]) }}"
                                            class="btn btn-block btn-dim btn-secondary"><span>{{ translate('View Plan') }}</span></a>
                                    </div>
                                </div><!-- .team -->
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div><!-- .col -->
                @endif
            @empty
        
            @endforelse
        </div>

    </div><!-- .nk-block -->

</div>

<div class="d-flex justify-content-center items-center m-auto">
<!-- BEGIN: Pagination -->
    {{ allClients()->links('vendor.pagination.bootstrap-4') }}
<!-- END: Pagination -->
</div>

    <!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="modalFormUser">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Add New User') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.clients.store') }}" 
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
                                <label class="form-label" for="name">{{ translate('Phone') }} *</label>
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
                                            placeholder="Phone"
                                            required="">
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
                                <label class="form-label" for="name">{{ translate('Company Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the company name of the customer') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="rest_name" 
                                            name="rest_name" 
                                            value="{{ old('rest_name') }}"
                                            placeholder="Company Name"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Company Address') }} *</label>
                                <span class="form-note">{{ translate('Specify the company address of the customer') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                            class="form-control" 
                                            id="rest_address" 
                                            name="rest_address" 
                                            value="{{ old('rest_address') }}"
                                            placeholder="Company Address"
                                            required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Package Duration Type') }} *</label>
                                <span class="form-note">{{ translate('Specify the URL if your main website is external.') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <select class="form-select" single="single"
                                        data-placeholder="Select package features" name="package_id" required="">
                                        @forelse (activePackages() as $package)
                                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : null }}>
                                                {{ $package->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
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

<input type="hidden" value="{{ route('check.domain') }}" id="check_domain_url">
<input type="hidden" value="{{ env('YOUR_DOMAIN') }}" id="base_url">

@endsection

@section('js')
    
@endsection
