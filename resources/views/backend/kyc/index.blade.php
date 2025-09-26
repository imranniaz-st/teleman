@extends('backend.layouts.master')

@section('title')
{{ translate('KYC Document') }}
@endsection

@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            @can('admin')
                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                    <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col tb-col-mb"><span
                                    class="sub-text">{{ translate('SL.') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span
                                    class="sub-text">{{ translate('NAME') }}</span></th>
                            <th class="nk-tb-col tb-col-mb"><span
                                    class="sub-text">{{ translate('DOCUMENT') }}</span></th>
                            <th class="nk-tb-col tb-col-md"><span
                                    class="sub-text">{{ translate('STATUS') }}</span></th>
                            <th class="nk-tb-col tb-col-md"><span
                                    class="sub-text">{{ translate('SUBMITTED AT') }}</span></th>
                            <th class="nk-tb-col tb-col-xl"><span class="sub-text"></span></th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(kyc_documents() as $document)
                            @if (getUserInfo($document->user_id) != null)
                                <tr class="nk-tb-item">
                                    <td class="nk-tb-col">
                                        <span>{{ $loop->iteration }}</span>
                                    </td>

                                    <td class="nk-tb-col tb-col-mb">
                                        {{ getUserInfo($document->user_id)->name }}
                                    </td>

                                    <td class="nk-tb-col tb-col-mb">
                                        <a href="{{ route('dashboard.kyc.review.document', [$document->user_id, Str::slug(getUserInfo($document->user_id)->name)]) }}"
                                            class="text-info">
                                            {{ translate('Review document') }}
                                        </a>
                                    </td>

                                    <td class="nk-tb-col tb-col-md">
                                        <span
                                            class="tb-status text-{{ $document->approval == 1 ? 'success' : 'danger' }}">
                                            @switch($document->approval)
                                                @case(0)
                                                        {{ translate('Pending') }}
                                                    @break
                                                @case(1)
                                                        {{ translate('Approved') }}
                                                    @break
                                                @case(2)
                                                        {{ translate('Rejected') }}
                                                    @break
                                                @default
                                                    
                                            @endswitch
                                        </span>
                                    </td>

                                    <td class="nk-tb-col tb-col-md">
                                        {{ $document->created_at->diffForHumans() }}
                                    </td>

                                    <td class="nk-tb-col nk-tb-col-tools">
                                        <ul class="nk-tb-actions gx-1">
                                            <li>
                                                <div class="drodown">
                                                    <a href="javascript:;" class="dropdown-toggle btn btn-icon btn-trigger"
                                                        data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li>
                                                                <a href="{{ route('dashboard.kyc.review.approved', [$document->user_id, Str::slug(getUserInfo($document->user_id)->name)]) }}">
                                                                    <em
                                                                        class="icon ni ni-check"></em><span>{{ translate('Approve') }}</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('dashboard.kyc.review.rejected', [$document->user_id, Str::slug(getUserInfo($document->user_id)->name)]) }}">
                                                                    <em
                                                                        class="icon ni ni-cross"></em><span>{{ translate('Reject') }}</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('dashboard.kyc.review.destroy', [$document->user_id, Str::slug(getUserInfo($document->user_id)->name)]) }}">
                                                                    <em
                                                                        class="icon ni ni-trash"></em><span>{{ translate('Remove') }}</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr><!-- .nk-tb-item  -->
                            @endif
                        @empty

                        @endforelse

                    </tbody>
                </table>
            @endcan

            @can('customer')

                @switch(kyc_document_approval(Auth::id()))
                    @case('approved')
                        <div class="text-center m-auto">
                            <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>
                                <lord-icon
                                    src="https://cdn.lordicon.com/lupuorrc.json"
                                    trigger="loop"
                                    delay="500"
                                    class="lord-icon-size">
                                </lord-icon>
                        </div>

                        <p class="text-center m-auto text-primary-alt">
                            {{ translate('Your KYC document is already approved') }} <b>{{ user_kyc_document(Auth::id())->created_at->diffForHumans() }}</b>. <br> {{ translate('Thank you for the verificatoin.') }}                     
                        </p>
                            
                        @break
                    @case('rejected')

                    <form action="{{ route('dashboard.kyc.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                        <div class="form-group"> <label class="form-label" for="customFileLabel">{{ translate('Upload the document') }}</label>
                            <div class="form-control-wrap">
                                <div class="form-file"> 
                                    <input type="file" 
                                            class="form-file-input" 
                                            id="customFile" 
                                            name="document"
                                            accept=
                                            "application/msword, application/vnd.ms-excel, application/pdf, image/*" required> 
                                    <label class="form-file-label" for="customFile">{{ translate('Choose file') }}</label> </div>
                                    <small>{{ translate('Please upload pdf,docx,jpg file. Maximum file size is 2 MB.') }}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">
                                {{ translate('Submit') }}
                            </button>
                        </div>

                    </form>
                        
                        @break
                    @case('pending')

                            <div class="text-center m-auto">
                                <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>
                                    <lord-icon
                                        src="https://cdn.lordicon.com/kbtmbyzy.json"
                                        trigger="loop"
                                        delay="500"
                                        class="lord-icon-size">
                                    </lord-icon>
                            </div>

                            <p class="text-center m-auto text-primary-alt">
                                {{ translate('You already submitted KYC document') }} <b>{{ user_kyc_document(Auth::id())->created_at->diffForHumans() }}</b>. <br> {{ translate('The document is under review queue. We will notify you via email.') }}                     
                            </p>

                        @break
                    @default
                        
                @endswitch

                

            @endcan
        </div>
    </div><!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')

@endsection
