@extends('backend.layouts.master')

@section('title')
{{ translate('LANGUAGES') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-toggle="modal" 
                            data-target="#modalForm">
                            {{ translate('Add New Language') }}
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
                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL') }}.</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CODE') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('LANGUAGE') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('COUNTRY') }}</span></th>
                        <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($languages as $language)
                    <tr class="nk-tb-item">
                        <td class="nk-tb-col  tb-col-mb">
                            <span>{{ $loop->iteration }}</span>
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ Str::upper($language->code) }}
                            @if (activeLanguage() == $language->code)
                            <span class="{{ activeLanguage() == $language->code ? 'badge bg-outline-success' : null }}" 
                                  title="{{ activeLanguage() == $language->code ? 'Active language' : null }}">
                                  {{ translate('active') }}
                                </span>
                                @endif

                                @can('admin')
                                @if (defaultLanguage() == $language->code)
                                    
                                <span class="{{ defaultLanguage() == $language->code ? 'badge bg-outline-dark' : null }}" 
                                    title="{{ defaultLanguage() == $language->code ? 'Default language' : null }}">
                                    {{ translate('default') }}
                                </span>
                                @endif
                                @endcan
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                                {{ $language->name }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            <div class="d-flex">
                                <img src="{{ flagAsset($language->image) }}" alt="{{ flagRename($language->image) }}" class="flag-w-5 rounded mx-1">    
                                {{ flagRename($language->image) }}
                            </div>
                        </td>
                    
                        <td class="nk-tb-col nk-tb-col-tools">
                            <ul class="nk-tb-actions gx-1">
                                <li>
                                    <div class="drodown">
                                        <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                @can('admin')
                                                    <li><a href="{{ route('language.translate', [$language->id, $language->code]) }}">
                                                            <em class="icon ni ni-sort-v">
                                                            </em><span>{{ translate('Go to translate') }}</span>
                                                        </a>
                                                    </li>
                                                    @if (defaultLanguage() != $language->code)
                                                    <li><a href="{{ route('language.default', [$language->id, $language->code]) }}">
                                                            <em class="icon ni ni-live">
                                                            </em><span>{{ translate('Make default') }}</span>
                                                        </a>
                                                    </li>
                                                    @endif
                                                @endcan
                                                @if (activeLanguage() != $language->code)
                                                <li><a href="{{ route('language.translate', [$language->id, $language->code]) }}" onclick="event.preventDefault();
                                                            document.getElementById('{{$language->name}}').submit()">
                                                        <em class="icon ni ni-swap">
                                                        </em><span>{{ translate('Translate now') }}</span>

                                                        <form id="{{$language->name}}" class="d-none" action="{{ route('language.change') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="code" value="{{$language->code}}">
                                                        </form>
                                                    </a>
                                                </li>
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
    </div>
    <!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Add New Language') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('language.store') }}" 
                        class="form-validate is-alter" 
                        method="POST" 
                        enctype="multipart/form-data">
                    @csrf


                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="site-name">{{ translate('Language Name') }} *</label>
                                <span class="form-note">{{ translate('Specify the name of the language') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                        class="form-control" 
                                        id="site-name" 
                                        name="name" 
                                        value="{{ old('name') }}"
                                        placeholder="Language Name"
                                            required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="site-code">{{ translate('Language Code') }} *</label>
                                <span class="form-note">{{ translate('Specify the name of the language code') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" 
                                        class="form-control" 
                                        id="site-code" 
                                        name="code" 
                                        value="{{ old('code') }}"
                                        placeholder="en"
                                            required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Select Country') }} *</label>
                                <span class="form-note">{{ translate('Specify the country.') }}.</span>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <select class="form-select" single="single"
                                        data-placeholder="Select country" name="image" required="">
                                        @foreach(readFlag() as $flag)
                                        @if ($loop->index > 1)
                                                <option value="{{ $flag }}"
                                                data-image="{{ flagAsset($flag) }}">
                                                    {{ flagRename($flag) }}
                                                </option>
                                        @endif
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