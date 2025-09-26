@extends('backend.layouts.master')

@section('title')
    {{ translate('Cron Jobs') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block">

<div class="nk-block-between-md g-4">
    <div class="nk-block-head-content">
        <div class="nk-block-des">
            <p>{{ translate('Here is all the cron jobs history') }}.</p>
        </div>
    </div>
    
</div>


<div class="card card-preview mt-3">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col"><span class="sub-text">#</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('CRON JOB NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('LAST SERVED') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('ISSUE') }}</span></th>
                </tr>
            </thead>
            <tbody>
                
                @foreach (CronJob_Last_Served() as $cron)
        
                    <tr class="nk-tb-item">
                    
                        <td class="nk-tb-col tb-col-mb tb-tnx-id">
                                <span>#{{ $loop->iteration }}</span>
                        </td>
                    
                        <td class="nk-tb-col tb-col-xl tb-col-mb">
                            <span class="font-weight-bold">{{ $cron->cron_name }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <span class="tb-status">{{$cron->created_at->diffForHumans() }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-md">
                            <span class="tb-status text-{{$cron->status ? 'success' : 'danger' }}">
                                {{$cron->status ? 'completed' : 'failed' }}
                            </span>
                        </td>

                        <td class="nk-tb-col tb-col-md">
                            <span class="tb-status font-weight-bold">{{ $cron->issue == null ? '--' : $cron->issue }}</span>
                        </td>
                    
                    </tr><!-- .nk-tb-item  -->
                @endforeach


            </tbody>
        </table>
    </div>
</div><!-- .card-preview -->



</div><!-- .nk-block -->
            
@endsection

@section('js')
    
@endsection