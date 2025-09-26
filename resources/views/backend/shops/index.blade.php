@extends('backend.layouts.master')

@section('title')
{{ translate('Shops') }}
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
                <button type="button" 
                        class="btn btn-secondary" 
                        data-toggle="modal" 
                        data-target="#modalForm">
                        {{ translate('Add New Phone Number') }}
                </button>
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
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE NUMBER') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('COUNTRY') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('MONTHLY FEE') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse (shops_available_number() as $number)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                        </div>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                            <span class="tb-amount">{{ $number->phone }}</span>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                            <span class="tb-amount">{{ Str::upper($number->country) }}</span>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                            <span class="tb-amount h6"><em class="icon ni ni-coins mx-1"></em>{{ $number->credit_cost }}</span>
                    </td>
                
                    <td class="nk-tb-col nk-tb-col-tools">
                        @can('customer')
                            <a href="{{ route('shop.purchase', [$number->id, Str::slug($number->phone)]) }}" class="btn-sm btn-secondary">{{ translate('Buy') }}</a>
                        @endcan
                        @can('admin')
                            <ul class="nk-tb-actions gx-1">
                                <li>
                                    <div class="drodown">
                                        <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="javascript:;" 
                                                    data-toggle="modal" 
                                                    data-target="#editModalForm-{{ $number->id }}">
                                                    <em class="icon ni ni-pen"></em><span>{{ translate('Edit') }}</span>
                                                    </a>
                                                </li>
                                                <li><a href="{{ route('shop.destroy', [ $number->id, Str::slug($number->country) ]) }}"><em class="icon ni ni-trash"></em><span>{{ translate('Trash') }}</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        @endcan
                    </td>
                </tr><!-- .nk-tb-item  -->
                <!-- Modal Form -->
                <div class="modal fade" tabindex="-1" id="editModalForm-{{ $number->id }}">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ translate('Update') }} {{ $number->phone }}</h4>
                                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                                    <em class="icon ni ni-cross"></em>
                                </a>
                            </div>
                            <div class="modal-body modal-body-lg">
                                <form action="{{ route('shop.update', $number->id) }}" 
                                        class="form-validate is-alter" 
                                        method="POST" 
                                        enctype="multipart/form-data">
                                    @csrf

                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label" for="name">{{ translate('Country') }} *</label>
                                                <span class="form-note">{{ translate('Specify the country') }}.</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <select class="form-select" single="single" data-placeholder="Select Country"
                                                name="country">
                                                <option value="">{{ translate('Select Country') }}</option>
                                                @foreach(getCountry() as $key => $country)
                                                    <option value="{{ Str::lower($country) }}" {{ Str::lower($number->country) == Str::lower($country) ? 'selected' : null }}>
                                                        {{ Str::upper($country) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label" for="phone">{{ translate('Phone Number') }}
                                                    *</label>
                                                <span
                                                    class="form-note">{{ translate('Specify the phone number') }}.</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                        value="{{ old('phone', $number->phone) }}" placeholder="Phone Number"
                                                        required="">
                                                    <small>{{ translate('Please provide country code with the phone number') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label" for="credit_cost">{{ translate('Credit Cost') }}
                                                    *</label>
                                                <span
                                                    class="form-note">{{ translate('Specify the phone number credit cost') }}.</span>
                                            </div>
                                        </div>

                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="credit_cost" name="credit_cost"
                                                        value="{{ old('credit_cost', $number->credit_cost) }}" placeholder="Credit Cost"
                                                        required="">
                                                    <small>{{ translate('Please provide the phone number credit cost amount') }}</small>
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
                <h4 class="modal-title">{{ translate('Add New Phone Number') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('shop.store') }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Country') }} *</label>
                                <span class="form-note">{{ translate('Specify the country') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select Country"
                                name="country">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach(getCountry() as $key => $country)
                                    <option value="{{ Str::lower($country) }}" {{ Str::lower(old('country')) == Str::lower($country) ? 'selected' : null }}>
                                        {{ Str::upper($country) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="phone">{{ translate('Phone Number') }}
                                    *</label>
                                <span
                                    class="form-note">{{ translate('Specify the phone number') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone') }}" placeholder="Phone Number"
                                        required="">
                                    <small>{{ translate('Please provide country code with the phone number') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="credit_cost">{{ translate('Credit Cost') }}
                                    *</label>
                                <span
                                    class="form-note">{{ translate('Specify the phone number credit cost') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="credit_cost" name="credit_cost"
                                        value="{{ old('credit_cost') }}" placeholder="Credit Cost"
                                        required="">
                                    <small>{{ translate('Please provide the phone number credit cost amount') }}</small>
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
