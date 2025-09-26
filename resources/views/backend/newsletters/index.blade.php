@extends('backend.layouts.master')

@section('title')
    {{ translate('Newsletters') }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="nk-block">

<div class="nk-block-between-md g-4">
    <div class="nk-block-head-content">
        <div class="nk-block-des">
            <p>{{ translate('Here is all the newsletter') }}.</p>

            @can('admin')
            <a href="{{ route('dashboard.newsletters.export') }}" 
                class="btn btn-secondary mt-2">
                <em class="icon ni ni-download mr-2"></em>
                {{ translate('Export Newsletters') }}
            </a>
            @endcan
            
        </div>
    </div>
</div>


<div class="card card-preview mt-3">
    <div class="card-inner">
        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
            <thead>
                <tr class="nk-tb-item nk-tb-head">
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">#</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('NAME') }}</span></th>
                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">{{ translate('PHONE') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('EMAIL') }}</span></th>
                    <th class="nk-tb-col tb-col-xl"><span class="sub-text">{{ translate('JOINED') }}</span></th>
                </tr>
            </thead>
            <tbody>

                
                @foreach (allNewsletters() as $newsletter)
        
                    <tr class="nk-tb-item">
                    
                        <td class="nk-tb-col tb-col-mb">
                            <span class="font-weight-bold">{{$loop->iteration }}</span>
                        </td>
                    
                        <td class="nk-tb-col tb-col-mb">
                            <span class="font-weight-bold">{{$newsletter->name }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-mb">
                            <span class="tb-status">+{{$newsletter->phone }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-md">
                            <span class="tb-status">{{$newsletter->email }}</span>
                        </td>

                        <td class="nk-tb-col tb-col-md">
                            <span class="tb-status text-success">{{ $newsletter->created_at->diffForHumans() }}</span>
                        </td>
                    
                    </tr><!-- .nk-tb-item  -->
                @endforeach

            </tbody>
        </table>
    </div>
</div><!-- .card-preview -->



</div><!-- .nk-block -->

@includeWhen(true, 'backend.dashboard.customer.components.support')
            
@endsection

@section('js')
    
@endsection