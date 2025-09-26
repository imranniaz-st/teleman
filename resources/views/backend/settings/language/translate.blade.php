@extends('backend.layouts.master')

@section('title')
{{ translate('TRANSLATE') }} {{ Str::upper($lang->name) }}
@endsection

@section('css')

@endsection

@section('content')

<form class="form-horizontal" action="{{ route('language.translate.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$lang->id}}">

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('language.index') }}" 
                            class="btn btn-secondary">
                            {{ translate('Languages') }}
                    </a>
                </li>

                <li class="preview-item">
                    <button type="button" 
                            onclick="copy()"
                            class="btn btn-primary">
                            {{ translate('Copy Texts') }}
                    </button>
                </li>

                <li class="preview-item">
                    <button type="submit" 
                            class="btn btn-primary">
                            {{ translate('Update Translation') }}
                    </button>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
        <div class="card-inner">
            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true" id="translation-table">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL') }}.</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('TEXT') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('TRANSLATE') }}</span></th>
                        </th>
                    </tr>
                </thead>
                <tbody>

                @foreach(openJSONFile('en') as $key => $value)
                    <tr class="nk-tb-item">
                        <td class="nk-tb-col tb-col-mb">
                            <span>{{ $loop->iteration }}</span>
                        </td>
                    
                        <td class="nk-tb-col tb-col-md key">{{ $key }}</td>
                    
                        <td class="nk-tb-col tb-col-md">
                                <input type="text" class="form-control value"
                                           name="translations[{{ $key }}]"
                                           @isset(openJSONFile($lang->code)[$key])
                                           value="{{ openJSONFile($lang->code)[$key] }}"
                                            @endisset
                                        placeholder="{{ $key }}">
                        </td>
                    
                    </tr><!-- .nk-tb-item  -->
                @endforeach
            
                </tbody>
            </table>
        </div>
    </div>
    <!-- .card-preview -->

</div>


</form>
@endsection

@section('js')

@endsection