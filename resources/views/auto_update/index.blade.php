@extends('backend.layouts.master')

@section('title')
    {{ translate('Application Upgrade') }}
@endsection

@section('css')
    
@endsection
    
@section('content')
<div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="@if(teleman_config('dashboard_ui') != 'EXTENDED') nk-wrap @endif nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content mh-auto">

                    <div class="modal fade" tabindex="-1" id="whatsNewModal">    
                        <div class="modal-dialog" role="document">        
                            <div class="modal-content">            
                                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">                
                                    <em class="icon ni ni-cross"></em>            
                                </a>            
                                <div class="modal-header d-block text-center">    
                                    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>
                                    <div class="d-block">
                                        <lord-icon
                                            src="https://cdn.lordicon.com/zrvxzslu.json"
                                            trigger="loop"
                                            style="width:200px;height:200px">
                                        </lord-icon>
                                    </div>

                                    <h5 class="modal-title fs-18px ff-alt fw-bold">{{ translate('What\'s new in the version ') }}{{ env('VERSION') }} ?</h5>            
                                </div>            
                                <div class="modal-body">                
                                    @forelse (whatsNewInTheUpdates() as $title => $update)
                                    <p class="fw-bold">{{ $title }}</p>
                                        <div class="px-2">
                                            @forelse ($update as $item)
                                                <p class="ff-mono">- {{ $item }}</p>
                                            @empty
                                                <p class="ff-mono">{{ translate('No New ') }} {{ $title }}</p> 
                                            @endforelse
                                        </div>
                                    @empty
                                    <p class="ff-mono">{{ translate('Known bug fix update.') }}</p>    
                                    @endforelse            
                                </div>            
                                <div class="modal-footer bg-light">                
                                    <span class="sub-text ff-mono">{{ translate('By') }} {{ application('site_name') ?? env('AUTHOR') }}</span>            
                                </div>        
                            </div>    
                        </div>
                    </div>

                    <div class="nk-block nk-block-middle wide-xs mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <h1 class="nk-error-head">{{ env('VERSION') }}</h1>
                            <p class="nk-error-text">{{translate('Before application update please make sure you have backed up your database and files.
                                        Upgrade may take time to finish. Do not close this window or disconnect your internet connection.')}}</p>
                            <a href="{{ route('auto.update.fire') }}" class="btn btn-lg btn-secondary mt-2"><em class="icon ni ni-swap-v mr-2"></em>{{ translate('Click-to-Upgrade') }}</a>
                        </div>
                    </div><!-- .nk-block -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
            
@endsection

@section('js')
    
@endsection