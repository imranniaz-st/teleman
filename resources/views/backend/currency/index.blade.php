@extends('backend.layouts.master')

@section('title')
    {{ translate('Currencies') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalForm">{{ translate('Add New Currency') }}</button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL') }}.</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CODE') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('SYMBOL') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('AMOUNT') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($currencies as $currency)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col  tb-col-mb">
                        <span>{{ $loop->iteration }}</span>
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        <span class="{{ $currency->default == 1 ? 'badge badge-success' : null }}" title="{{ $currency->default == 1 ? 'Default currency' : null }}">{{ $currency->symbol }}</span>
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                            {{ $currency->icon }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                            {{ $currency->name ?? 'N/A' }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                            {{ $currency->icon }}{{ $currency->amount ?? 'N/A' }}
                    </td>
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route('dashboard.currency.update', $currency->id) }}"><em class="icon ni ni-sort-v"></em><span>{{ translate('Update') }}</span></a></li>
                                            @if($currency->default != 1)
                                            <li><a href="{{ route('dashboard.currency.default', $currency->code) }}"><em class="icon ni ni-focus"></em><span>{{ translate('Set Default') }}</span></a></li>
                                            <li><a href="{{ route('dashboard.currency.destroy', $currency->id) }}"><em class="icon ni ni-trash"></em><span>{{ translate('Delete') }}</span></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr><!-- .nk-tb-item  -->
            @endforeach
           
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
                        <h4 class="modal-title">{{ translate('Add New Currency') }}</h4>
                        <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                            <em class="icon ni ni-cross"></em>
                        </a>
                    </div>
                    <div class="modal-body modal-body-lg">
                        <form action="{{ route('dashboard.currency.store') }}" 
                              class="form-validate is-alter" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3 align-center">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Select Currency') }} *</label>
                                        <span class="form-note">{{ translate('Specify the URL if your main website is external') }}.</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <select class="form-select" single="single"
                                                data-placeholder="Select package features" name="code" required="">
                                                @foreach(config('money') as $key => $money)
                                                        <option value="{{ $key }}"> {{ $money['name'] }}({{ $key }})</option>
                                                @endforeach
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
    
@endsection

@section('js')
    
@endsection