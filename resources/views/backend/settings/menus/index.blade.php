@extends('backend.layouts.master')

@section('title')
    {{ translate('Menus') }}
@endsection

@section('css')

@endsection
    
@section('content')


<div class="nk-block nk-block-lg">

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.menu-builder.create') }}" class="btn btn-secondary">{{ translate('Add New Menu') }}</a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->
</div>

<div class="card card-preview">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col nk-tb-col-check">
                        <div class="custom-control custom-control-sm custom-checkbox notext">
                            <input type="checkbox" class="custom-control-input" id="uid">
                            <label class="custom-control-label" for="uid"></label>
                        </div>
                    </th>
                    <th class="nk-tb-col"><span class="sub-text">{{ translate('Menu') }}</span></th>
                    <th class="nk-tb-col nk-tb-col-tools text-right">
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                
                <tr class="nk-tb-item">
                    <td class="nk-tb-col nk-tb-col-check">
                        <div class="custom-control custom-control-sm custom-checkbox notext">
                            <input type="checkbox" class="custom-control-input" id="uid{{ $menu->id }}">
                            <label class="custom-control-label" for="uid{{ $menu->id }}"></label>
                        </div>
                    </td>
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{!! Str::upper(substr($menu->name, 0, 2)) !!}</span>
                            </div>
                            <div class="user-info">
                                <span class="tb-lead">{!! $menu->name !!} <span class="dot dot-success d-md-none ml-1"></span></span>
                            </div>
                        </div>
                    </td>
                    
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="{{ route('dashboard.menu-builder.create') }}?menu={{$menu->id}}"><em class="icon ni ni-focus"></em><span>{{ translate('Quick View') }}</span></a></li>
                                           </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr><!-- .nk-tb-item  -->

                @empty
                    
                @endforelse
            </tbody>
        </table>
    </div>
</div><!-- .card-preview -->
</div> <!-- nk-block -->
            
@endsection

@section('js')
    
@endsection