@extends('backend.layouts.master')

@section('title')
    {{ translate('IVR Campaign') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                    <a href="{{ route('dashboard.ivr.create') }}" class="btn btn-secondary">{{ translate('Create New IVR') }}</a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CAMPAIGN') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('AUDIO') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse (ivr_data() as $ivr)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <span>{{ $loop->iteration }}</span>
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                        {{ $ivr->ivr_name }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-mb">
                        {{ campaign_name($ivr->campaign_id) }}
                    </td>
                  
                    <td class="nk-tb-col tb-col-md">
                        PLAY AUDIO
                        {{-- TODO --}}
                    </td>
                    
                    <td class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li>
                                <div class="drodown">
                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="link-list-opt no-bdr">
                                            {{-- <li>
                                                <a href="{{ route('dashboard.campaign.destroy', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                    <em class="icon ni ni-trash"></em><span>{{ translate('Remove') }}</span>
                                                </a>
                                            </li> --}}
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

</div>
<!-- END: Large Slide Over Toggle -->
    
@endsection

@section('js')
    
@endsection