<!DOCTYPE html>
<html>

<head>
    <title>{{ translate('Inbound Calling') }}</title>
    @includeWhen(true, 'backend.layouts.components.meta')
    @includeWhen(true, 'backend.layouts.components.css')
</head>

<body>

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-ibx nk-ibx-no-height">
                    <div class="nk-ibx-aside" data-content="inbox-aside" data-toggle-overlay="true" data-toggle-screen="lg">
                        <div class="nk-ibx-head m-auto">
                            <div class="nk-header-brand pt-0 pb-0 w-100">
                                <a href="{{ route('backend') }}" class="logo-link">
                                    <img class="logo-light logo-img max-height-35" src="{{ darkLogo() }}" srcset="{{ darkLogo() }} 2x" alt="{{ appName() }}">
                                    <img class="logo-dark logo-img max-height-35" src="{{ logo() }}" srcset="{{ logo() }} 2x" alt="{{ appName() }}">
                                </a>
                            </div><!-- .nk-header-brand -->
                        </div>
                        <div class="nk-ibx-nav" data-simplebar>
                            <div class="nk-ibx-nav-head">
                                <h6 class="title ff-mono">{{ translate('IN QUEUE') }}</h6>
                                <a class="link ff-mono" href="javascript:;" id="countQueueWaiting">
                                    {{ countQueueWaiting() }}
                                </a>
                            </div>
                            
                            <ul class="nk-ibx-contact" id="getQueue">
                                @forelse (getQueue($my_number) as $queue)

                                <li>
                                    <div class="user-card">
                                        <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                                            <span>{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="user-info">
                                            <span class="lead-text">{{ $queue->caller_number }}</span>
                                            <span class="sub-text" id="countdown" data-calling-time="{{ $queue->created_at }}"></span>
                                        </div>
                                    </div>
                                </li>
                                    
                                @empty
                                    
                                @endforelse
                            </ul>
                        </div>
                    </div><!-- .nk-ibx-aside -->
                    <div class="nk-ibx-body bg-white">
                        <div class="nk-chat-head">
                            <ul class="nk-chat-head-info border-right">
                                <li class="nk-chat-head-user">
                                    <div class="user-card">
                                        <div class="user-info">
                                            <div class="lead-text">{{ get_user_identity(auth()->id()) }}</div>
                                            <div class="sub-text"><span class="d-sm-inline mr-1" id="dialer-message">Verifying</span></div>
                                        </div>
                                    </div>
                                    
                                </li>
                                <li><a href="{{ route('dashboard.all.recordings') }}" class="btn btn-icon btn-trigger text-success"><em class="icon ni ni-download"></em></a></li>
                                {{-- <li><a href="javascript:;" class="btn btn-icon btn-trigger text-info"><em class="icon ni ni-wifi"></em></a></li> --}}
                                {{-- <li><a href="javascript:;" class="btn btn-icon btn-trigger text-danger"><em class="icon ni ni-wifi-off"></em></a></li> --}}
                            </ul>
                            <ul class="nk-chat-head-tools d-none" id="dialerpad">
                                <li>
                                    <em class="icon ni ni-copy"></em>
                                    <a href="javascript:;" 
                                       class="text-primary fw-bold ff-alt copy-cat" 
                                       data-toggle="tooltip" 
                                       data-placement="top" 
                                       title="Tooltip on top"
                                       id="display-number">
                                        {{-- Number goes here --}}
                                    </a>
                                </li>
                                <li>
                                    <span 
                                        id="dialer-timer"
                                        class="text-secondary fw-bold ff-alt"></span>
                                </li>
                                <li><a href="javascript:;" class="btn btn-icon btn-trigger text-success" id="button-call"><em class="icon ni ni-call-fill"></em></a></li>
                                <li><a href="javascript:;" class="btn btn-icon btn-trigger text-danger" id="button-hangup"><em class="icon ni ni-cross-fill-c"></em></a></li>
                            </ul>
                            
                        </div>
                        <div class="nk-ibx-list" data-simplebar>

                            @if (count(getCallHistories(get_user_identity_id(auth()->id()))) > 0)
        
                                <div class="w-100">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="search_input" placeholder="{{ translate('Search here') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-preview overflow-auto">
                                        <div class="card-inner">
                                            <table class="datatable-init nk-tb-list nk-tb-ulist search-table" data-auto-responsive="true">
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
                                                @forelse (getCallHistories(get_user_identity_id(auth()->id())) as $history)
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
                                                            @if (checkRecordFileExists($history->caller_uuid) == 1)

                                                            <a class="audio {skin:'black', autoPlay:false,showRew:false, showTime:true, loop: false, downloadable: true}" 
                                                                href="{{ asset('vc/recording_' . $history->caller_uuid . '.mp3') }}">
                                                            </a>
                                                            <a target="_blank" href="{{ route('analyze.the.call.record', $history->caller_uuid) }}" title="{{ translate('Analyze Audio Record') }}" class="btn btn-icon btn-dim btn-sm btn-outline-light btn-round">
                                                                <em class="icon ni ni-activity"></em>
                                                            </a>

                                                            @else
                                                                {{ translate('No Audio Record') }}
                                                            @endif

                                                        </td>

                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status text-bold">
                                                                {{ Str::upper($history->status) }}
                                                            </span>
                                                        </td>
                                                        
                                                    </tr><!-- .nk-tb-item  -->
                                                @empty
                                                        
                                                @endforelse
                                            
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            @endif
                        
                        </div><!-- .nk-ibx-body -->
                        
                    </div><!-- .nk-ibx -->

                    {{-- COMING SOON --}}
                    <div class="nk-ibx-aside d-none" data-content="inbox-aside" data-toggle-overlay="true" data-toggle-screen="lg">
                        <div class="nk-ibx-head m-auto">
                            <div class="nk-header-brand">
                                <a href="{{ route('backend') }}" class="logo-link">
                                    <img class="logo-light logo-img" src="{{ darkLogo() }}" srcset="{{ darkLogo() }} 2x" alt="{{ appName() }}">
                                    <img class="logo-dark logo-img" src="{{ logo() }}" srcset="{{ logo() }} 2x" alt="{{ appName() }}">
                                </a>
                            </div><!-- .nk-header-brand -->
                        </div>
                        <div class="nk-ibx-nav" data-simplebar>
                            <div class="nk-ibx-nav-head">
                                <h6 class="title">{{ translate('IN QUEUE') }}</h6>
                                <a class="link" href="javascript:;">
                                    1
                                </a>
                            </div>
                            
                            <ul class="nk-ibx-contact">
                                <li>
                                    <a href="#">
                                        <div class="user-card">
                                            <div class="user-avatar"><img src="./images/avatar/a-sm.jpg" alt=""></div>
                                            <div class="user-info">
                                                <span class="lead-text">Abu Bin Ishtiyak</span>
                                                <span class="sub-text">CEO of Softnio</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                        <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="#"><span>View Profile</span></a></li>
                                                <li><a href="#"><span>Send Email</span></a></li>
                                                <li><a href="#"><span>Start Chat</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div><!-- .nk-ibx-aside -->
                    {{-- COMING SOON --}}

                </div>
            </div>
        </div>
    </div>
</div>

    {{-- HIDDEN::START --}}
    <div id="info" class="d-none">
        <p class="instructions">Twilio Client</p>
        <div id="client-name"></div>
        <div id="output-selection">
            <label>Ringtone Devices</label>
            <select id="ringtone-devices" multiple></select>
            <label>Speaker Devices</label>
            <select id="speaker-devices" multiple></select><br />
            <a id="get-devices">Seeing unknown devices?</a>
        </div>
    </div>
    <div id="call-controls" class="d-none">
        <p class="instructions">Make a Call:</p>
        <div id="volume-indicators">
            <label>Mic Volume</label>
            <div id="input-volume"></div><br /><br />
            <label>Speaker Volume</label>
            <div id="output-volume"></div>
        </div>
    </div>
    <div id="log" class="d-none"></div>
    <div id="client-name" class="d-none"></div>
    {{-- HIDDEN::ENDS --}}


    <input type="hidden" id="capability_token" value="{{ route('dialer.token') }}">
    <input type="hidden" id="create_call_history_url" value="{{ route('create.call.hostory') }}">
    <input type="hidden" id="my_number" value="{{ $my_number }}">
    <input type="hidden" id="find_contact_url" value="{{ route('dashboard.contact.find') }}">
    <input type="hidden" id="agent_status_update_url" value="{{ route('dashboard.agent.status.update') }}">
    <input type="hidden" id="get_queue_list_url" value="{{ route('dashboard.get.queue.list') }}">

    <script type="text/javascript" src="{{ asset('dialerpad/js/twilio.js') }}"></script>
    <script src="{{ asset('dialerpad/js/jquery.min.js') }}"></script>
    <script src="{{ asset('mini_audio_player/js/jquery.mb.miniAudioPlayer.js') }}"></script>
    <script src="{{ asset('dialerpad/js/quickstart.js') }}"></script>
    <x:notify-messages />
    @notifyJs
</body>

</html>
