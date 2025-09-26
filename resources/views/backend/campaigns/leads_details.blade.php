@extends('backend.layouts.master')

@section('title')
{{ translate('Campaign Leads Details') }} ⇢ {{ campaign_name($campaign_id) }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">

    <div class="card card-preview">
        <div class="card-inner">
            <ul class="nav nav-tabs mt-n3">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-call"></em><span>{{ translate('PICKED') }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-bell"></em><span>{{ translate('BUSY') }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-bell-off"></em></em><span>{{ translate('SWITCHED OFF') }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-call-alt"></em><span>{{ translate('LEADS') }}</span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tabItem5">

                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('NAME') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CALLED AT') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse (voice_campaign($campaign_id, 'p') as $campaign)
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span>{{ $loop->iteration }}</span>
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->name ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-mb">
                                    {{ $campaign->contact->phone ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-mb">
                                    {{ $campaign->created_at->format('d-m-Y H:i:s') }}
                                </td>
                            </tr><!-- .nk-tb-item  -->
                        @empty
                                
                        @endforelse
                    
                        </tbody>
                    </table>

                </div>
                <div class="tab-pane" id="tabItem6">
                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CALLED AT') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse (voice_campaign($campaign_id, 'b') as $campaign)
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span>{{ $loop->iteration }}</span>
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->name ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->phone ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->created_at->format('d-m-Y H:i:s') }}
                                </td>
                            </tr><!-- .nk-tb-item  -->
                        @empty
                                
                        @endforelse
                    
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tabItem7">
                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CALLED AT') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse (voice_campaign($campaign_id, 's') as $campaign)
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span>{{ $loop->iteration }}</span>
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->name ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->phone ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->created_at->format('d-m-Y H:i:s') }}
                                </td>
                            </tr><!-- .nk-tb-item  -->
                        @empty
                                
                        @endforelse
                    
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="tabItem8">
                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                                <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('CALLED AT') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse (voice_campaign($campaign_id, 'l') as $campaign)
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span>{{ $loop->iteration }}</span>
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->name ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->contact->phone ?? 'N/A' }}
                                </td>
                            
                                <td class="nk-tb-col tb-col-md">
                                    {{ $campaign->created_at->format('d-m-Y H:i:s') }}
                                </td>
                            </tr><!-- .nk-tb-item  -->
                        @empty
                                
                        @endforelse
                    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')

@endsection
