@extends('backend.layouts.master')

@section('title')
    {{ translate('Call History & Recordings') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block">

    <p>
        <a href="{{ route('dashboard.all.recordings') }}" class="text-success">
            {{ translate('Click to download call recordings') }}
        </a>
        <a href="{{ route('dashboard.all.recordings') }}" class="btn btn-icon btn-trigger text-success"><em class="icon ni ni-download"></em></a>
    </p>

<div class="card card-preview mt-3">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('SL.') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CALLER PHONE') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('INCOMING') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('HANGUP') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('DURATION') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('RECORDING') }}</span></th>
                    <th class="nk-tb-col tb-col-md"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    </th>
                </tr>
            </thead>
            <tbody>

            @forelse (getAllCallHistories() as $history)
                <tr class="nk-tb-item">
                    <td class="nk-tb-col">
                        <div class="user-card">
                            <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                        </div>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                        <span class="tb-amount">{{ $history->caller_number ?? '--' }}</span>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                        <span class="tb-amount">{{ $history->pick_up_time ?? '--' }}</span>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                        <span class="tb-amount">{{ $history->hang_up_time ?? '--' }}</span>
                    </td>
                
                    <td class="nk-tb-col tb-col-mb">
                        <span class="tb-amount">
                            @if ($history->pick_up_time && $history->hang_up_time)
                                {{ calculateCallDuration($history->pick_up_time, $history->hang_up_time) }} 
                            @else
                            --
                            @endif
                        </span>
                    </td>
                
                    <td class="nk-tb-col tb-col-md">
                        @if ($history->record_file != null)

                        <a class="audio {skin:'black', autoPlay:false,showRew:false, showTime:true, loop: false, downloadable: true}" 
                            href="{{ asset('vc/recording_' . $history->caller_uuid . '.mp3') }}">
                        </a>
                        <a target="_blank" href="{{ route('analyze.the.call.record', $history->record_file) }}" title="{{ translate('Analyze Audio Record') }}" class="btn btn-icon btn-dim btn-sm btn-outline-light btn-round">
                            <em class="icon ni ni-activity"></em>
                        </a>

                        @else
                            {{ translate('No Audio Record') }}
                        @endif

                    </td>

                    <td class="nk-tb-col tb-col-md">
                        <span class="tb-status text-bold">
                            {{ translate(Str::upper($history->status)) }} 
                        </span>
                    </td>
                    
                </tr><!-- .nk-tb-item  -->
            @empty
                    
            @endforelse

            </tbody>
        </table>
    </div>
</div><!-- .card-preview -->



</div><!-- .nk-block -->

@endsection

@section('js')
    
@endsection