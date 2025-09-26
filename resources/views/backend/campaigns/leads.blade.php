@extends('backend.layouts.master')

@section('title')
    {{ translate('Campaign Leads') }}
@endsection


@section('css')
    
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">

    <div class="card card-preview">
        <div class="card-inner">
            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                <thead>
                    <tr class="nk-tb-item nk-tb-head">
                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('PROVIDER') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CONTACTS') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('PICKED') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('BUSY') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('SW. OFF') }}</span></th>
                        <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('LEAD') }}</span></th>
                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('RATIO') }}(%)</span></th>
                        <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @forelse (campaigns() as $campaign)
                    <tr class="nk-tb-item">
                        <td class="nk-tb-col">
                            <span>{{ $loop->iteration }}</span>
                        </td>
                    
                        <td class="nk-tb-col tb-col-mb">
                            {{ $campaign->name }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ provider_name($campaign->provider) }}({{ provider_phone($campaign->provider) }})
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ leads_data($campaign->id)['total'] }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ leads_data($campaign->id)['picked'] }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ leads_data($campaign->id)['busy'] }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ leads_data($campaign->id)['swiched_off'] }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-md">
                            {{ leads_data($campaign->id)['lead'] }}
                        </td>
                    
                        <td class="nk-tb-col tb-col-mb">
                            <em class="icon ni ni-chevrons-{{ leads_data($campaign->id)['lead_percentage_expectation']== true ? 'up text-success' : 'down text-danger' }}"></em>
                            {{ leads_data($campaign->id)['lead_percentage'] }} 
                            ({{ campaign_expectation_leads($campaign->id) }}%) 
                        </td>
                        
                        <td class="nk-tb-col nk-tb-col-tools">
                            <ul class="nk-tb-actions gx-1">
                                <li>
                                    <div class="drodown">
                                        <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                <li>
                                                    <a href="{{ route('dashboard.campaign.leads_details', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                        <em class="icon ni ni-eye"></em><span>{{ translate('More Details') }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('dashboard.campaign.leads_export', [$campaign->id, Str::slug($campaign->name)]) }}">
                                                        <em class="icon ni ni-download"></em><span>{{ translate('Export') }}</span>
                                                    </a>
                                                </li>
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