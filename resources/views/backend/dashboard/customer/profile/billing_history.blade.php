@extends('backend.layouts.master')

@section('title')
    {{ translate('Billing History') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block">

<div class="nk-block-between-md g-4">
    <div class="nk-block-head-content">
        <div class="nk-block-des">
            @can('admin')
                <p>{{ translate('Here is all the payment history of users') }}.</p>
                
            @endcan

            @can('customer')
                <p>{{ translate('Here is your payment history of account') }}.</p>
            @endcan

            <a href="{{ route('dashboard.payment.histories.export') }}" 
                    class="btn btn-secondary mt-2">
                    <em class="icon ni ni-download mr-2"></em>
                    {{ translate('Export Payment Histories') }}
            </a>
        </div>
    </div>

    @can('customer')
        <div class="nk-block-head-content">
            <ul class="nk-block-tools gx-3">
                <li><a href="{{ route('frontend.pricing') }}" class="btn btn-white btn-dim btn-outline-primary">
                    <em class="icon ni ni-reports"></em><span><span class="d-none d-sm-inline-block">{{ translate('Renew') }}</span> 
                    {{ translate('Subscription') }}</span></a></li>
            </ul>
        </div>
    @endcan
    
</div>


<div class="card card-preview mt-3">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col"><span class="sub-text">#</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('BILL FOR') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('ISSUE DATE') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('DUE DATE') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('TOTAL') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('STATUS') }}</span></th>
                    @can('admin')
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('ACTION') }}</span></th>
                    @endcan
                </tr>
            </thead>
            <tbody>

            @can('customer')
                
                @foreach (userPaymentHistory() as $history)
        
                    <tr class="nk-tb-item">
                    
                        <td class="nk-tb-col tb-col-md tb-tnx-id">
                            <a href="{{ route('dashboard.profile.billing.invoice', $history->invoice) }}">
                                <span>#{{ $history->invoice }}</span>
                            </a>
                        </td>
                    
                        <td class="nk-tb-col tb-col-xl">
                            <span class="font-weight-bold">{{PackageDetails($history->package_id)->name ?? null }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <span class="tb-status">{{$history->start_at }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-xl tb-col-mb">
                            <span class="tb-status">{{$history->end_at }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-xl tb-col-mb">
                            <span class="tb-status font-weight-bold">{{ price($history->amount) }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <span class="tb-status text-{{ $history->payment_status == 'pending' ? 'primary' : 'success' }}">{{ $history->payment_status }}</span>
                        </td>
                    
                    </tr><!-- .nk-tb-item  -->
                @endforeach

            @endcan

            @can('admin')
                
                @foreach (customerPaymentHistory() as $history)
        
                    <tr class="nk-tb-item">
                    
                        <td class="nk-tb-col tb-col-mb tb-tnx-id">
                            <a href="{{ route('dashboard.profile.billing.invoice', $history->invoice) }}">
                                <span>#{{ $history->invoice }}</span>
                            </a>
                        </td>
                    
                        <td class="nk-tb-col tb-col-xl tb-col-mb">
                            <span class="font-weight-bold">{{PackageDetails($history->package_id)->name ?? null }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <span class="tb-status">{{$history->start_at }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-md">
                            <span class="tb-status">{{$history->end_at }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-md">
                            <span class="tb-status font-weight-bold">{{ price($history->amount) }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <span class="tb-status text-{{ $history->payment_status == 'pending' ? 'primary' : 'success' }}">{{ $history->payment_status }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <a href="{{ route('dashboard.profile.billing.history.destroy', $history->id) }}" class="btn-sm btn-danger btn-dim btn-outline-primary">
                                <em class="icon ni ni-trash"></em>
                            </a>
                        </td>
                    
                    </tr><!-- .nk-tb-item  -->
                @endforeach

            @endcan

            </tbody>
        </table>
    </div>
</div><!-- .card-preview -->



</div><!-- .nk-block -->

@includeWhen(true, 'backend.dashboard.customer.components.support')
            
@endsection

@section('js')
    
@endsection