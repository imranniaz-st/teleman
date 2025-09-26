@extends('backend.layouts.master')

@section('title')
{{ translate('GEO Permissions') }}
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
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm"><em
                            class="icon ni ni-users mr-2"></em>{{ translate('Add New Country') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
        <div class="card-inner">
            <p>{{ translate('Check the call cost list here:') }} <a href="{{ asset('call_cost.pdf') }}" class="text-danger" target="_blank">{{ translate('click here') }}</a></p>
        </div>
    </div>

    @endcan

    <div class="card card-preview">
        <div class="card-inner">

            <table class="datatable-init nowrap nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col"><span class="sub-text">{{ translate('COUNTRY') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CODE') }}</span></th>
                        @can('admin')
                        <th class="nk-tb-col tb-col-mb"><span
                            class="sub-text">{{ translate('TWILIO COST') }}</span></th>
                        @endcan
                        <th class="nk-tb-col tb-col-mb"><span
                                class="sub-text">{{ translate('YOUR COST') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span
                                class="sub-text">{{ translate('SMS COST') }}</span></th>
                        @can('admin')
                        <th class="nk-tb-col tb-col-lg tb-col-md"><span
                                class="sub-text">{{ translate('UPDATE') }}</span></th>
                        @endcan     
                    </tr>
                </thead>
                <tbody>
                    @forelse(twilio_call_costs() as $cost)
                        <tr class="nk-tb-item">
                            <td class="nk-tb-col">
                                <div class="user-card">
                                    <div class="user-info">
                                        <span class="tb-lead">{{ Str::upper($cost->country) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="nk-tb-col tb-col-md" data-order="{{ $cost->code }}">

                                <form action="{{ route('dashboard.twilio.call.cost.update', $cost->id) }}" method="POST">
                                    @csrf

                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" 
                                            name="code" 
                                            class="form-control form-control-xl form-control-outlined @error('code') is-invalid @enderror" 
                                            id="code{{  $cost->id }}"
                                            value="{{ $cost->code }}" required="" autocomplete="off" @cannot('admin') disabled @endcannot>

                                            <label class="form-label-outlined" for="code{{  $cost->id }}">{{ translate('Country Code') }}</label>

                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                    </div>
                                </div><!-- .foem-group -->
                            </td>
                            @can('admin')
                            <td class="nk-tb-col tb-col-mb" data-order="{{ $cost->twilio_cost }}">

                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" 
                                            name="twilio_cost" 
                                            class="form-control form-control-xl form-control-outlined @error('twilio_cost') is-invalid @enderror" 
                                            id="twilio_cost{{ $cost->id }}"
                                            value="{{ $cost->twilio_cost }}" required="" autocomplete="off">

                                            <label class="form-label-outlined" for="twilio_cost{{ $cost->id }}">{{ translate('Twilio Cost') }}</label>

                                            @error('twilio_cost')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                    </div>
                                </div><!-- .foem-group -->
                            </td>
                            @endcan
                            <td class="nk-tb-col tb-col-lg tb-col-mb">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" 
                                            name="teleman_cost" 
                                            class="form-control form-control-xl form-control-outlined @error('teleman_cost') is-invalid @enderror" 
                                            id="teleman_cost{{ $cost->id }}"
                                            value="{{ $cost->teleman_cost }}" required="" autocomplete="off" @cannot('admin') disabled @endcannot>

                                            <label class="form-label-outlined" for="teleman_cost{{ $cost->id }}">{{ translate('Your Cost') }}</label>

                                            @error('teleman_cost')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                    </div>
                                </div><!-- .foem-group -->
                            </td>
                            
                            <td class="nk-tb-col tb-col-lg tb-col-mb">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" 
                                            name="teleman_sms_cost" 
                                            class="form-control form-control-xl form-control-outlined @error('teleman_sms_cost') is-invalid @enderror" 
                                            id="teleman_sms_cost{{ $cost->id }}"
                                            value="{{ $cost->twilio_sms_cost->teleman_sms_cost ?? null }}" required="" autocomplete="off" @cannot('admin') disabled @endcannot>

                                            <label class="form-label-outlined" for="teleman_sms_cost{{ $cost->id }}">{{ translate('SMS Cost') }}</label>

                                            @error('teleman_sms_cost')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                    </div>
                                </div><!-- .foem-group -->
                            </td>

                            @can('admin')

                            <td class="nk-tb-col tb-col-md">
                                <button type="submit"
                                    class="btn-sm btn-secondary">
                                    <em class="icon ni ni-save"></em>
                                </button>
                            </form>
                            <a href="{{ route('dashboard.twilio.call.cost.destroy', $cost->id) }}"
                                    class="btn-sm btn-danger">
                                    <em class="icon ni ni-trash"></em>
                            </a>
                            </td>

                            @endcan

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
                <h4 class="modal-title">{{ translate('Create Cost') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.twilio.call.cost.store') }}" class="form-validate is-alter"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-12">
                            <label class="form-label" for="name">{{ translate('Country') }} *</label>
                            <select class="form-select" single="single" data-placeholder="Select Country"
                                name="country">
                                @foreach(getCountry() as $country)
                                    <option value="{{ Str::lower($country) }}">{{ Str::upper($country) }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="form-note">{{ translate('Check here') }} 
                                <a href="https://console.twilio.com/us1/develop/voice/settings/geo-permissions" target="_blank" class="text-danger">Twilio coverage areas</a>
                            </span>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-12">
                            <div class="form-group mt-2">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                        name="code" 
                                        class="form-control form-control-xl form-control-outlined @error('code') is-invalid @enderror" 
                                        id="code"
                                        value="{{ old('code') }}" required="" autocomplete="off">

                                        <label class="form-label-outlined" for="code">{{ translate('Enter Country Code') }}</label>

                                        @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                </div>
                            </div><!-- .foem-group -->
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-12">
                            <div class="form-group mt-2">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                        name="twilio_cost" 
                                        class="form-control form-control-xl form-control-outlined @error('twilio_cost') is-invalid @enderror" 
                                        id="twilio_cost"
                                        value="{{ old('twilio_cost') }}" required="" autocomplete="off">

                                        <label class="form-label-outlined" for="twilio_cost">{{ translate('Enter Twilio Call Cost') }}</label>

                                        @error('twilio_cost')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                </div>
                            </div><!-- .foem-group -->
                        </div>
                    </div>


                    <div class="row g-3 align-center">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="form-control-wrap mt-2">
                                    <input type="text" 
                                        name="teleman_cost" 
                                        class="form-control form-control-xl form-control-outlined @error('teleman_cost') is-invalid @enderror" 
                                        id="teleman_cost"
                                        value="{{ old('teleman_cost') }}" required="" autocomplete="off">

                                        <label class="form-label-outlined" for="teleman_cost">{{ translate('Enter Your Call Cost') }}</label>

                                        @error('teleman_cost')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div><!-- .foem-group -->
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
