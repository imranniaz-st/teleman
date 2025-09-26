@extends('backend.layouts.master')

@section('title')
{{ campaign_name($campaign_id) }} ⇢ {{ translate('Total Contacts') }}
({{ campaign_emails($campaign_id)->count() }})
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('dialer/site.css') }}">
<link rel="stylesheet" href="{{ asset('dialer/mobile.css') }}">
@endsection

@section('content')

<div class="nk-block nk-block-lg">

    <pre class="text-right">
        <strong><span class="btn-xs btn-secondary">{{ translate('P') }}</span> = {{ translate('picked') }}, <span class="btn-xs btn-secondary">{{ translate('B') }}</span> = {{ translate('busy') }} , <span class="btn-xs btn-secondary">{{ translate('S') }}</span> = {{ translate('switched off') }}, <span class="btn-xs btn-secondary">{{ translate('L') }}</span> = {{ translate('lead') }} </strong>
    </pre>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-preview">
                <div class="card-inner">
                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="true">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                                <th class="nk-tb-col tb-col-mb"><span
                                        class="sub-text">{{ translate('SL.') }}</span>
                                </th>
                                <th class="nk-tb-col tb-col-md"><span
                                        class="sub-text">{{ translate('NAME') }}</span>
                                </th>
                                <th class="nk-tb-col tb-col-mb"><span
                                        class="sub-text">{{ translate('NUMBER') }}</span></th>

                                <th class="nk-tb-col tb-col-mb"><span
                                        class="sub-text">{{ translate('STATUS') }}</span></th>

                                <th class="nk-tb-col tb-col-mb"><span
                                        class="sub-text">{{ translate('Make Call') }}</span></th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse(campaign_emails($campaign_id) as $contact)
                                <tr class="nk-tb-item">
                                    <td class="nk-tb-col">
                                        <span>{{ $loop->iteration }}</span>
                                    </td>

                                    <td class="nk-tb-col tb-col-md">
                                        <a href="javascript:;" title="{{ translate('Click to see log') }}" data-toggle="modal" data-target="#contact-{{ $loop->iteration }}">
                                            {{ $contact->contacts->name }}
                                        </a>

                                        <!-- Modal Form -->
                                        <div class="modal fade" tabindex="-1" id="contact-{{ $loop->iteration }}">
                                            <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $contact->contacts->phone }} ⇢ {{ translate('Calling Logs') }}</h4>
                                                        <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                                                            <em class="icon ni ni-cross"></em>
                                                        </a>
                                                    </div>
                                                    <div class="modal-body modal-body-lg">
                                                        
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">{{ translate('Flow') }}</th>
                                                                    <th scope="col">{{ translate('Status') }}</th>
                                                                    <th scope="col">{{ translate('Date & Time') }}</th>
                                                                    <th scope="col">{{ translate('Agent Name') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse (voice_campaign_status_log_update($campaign_id, $contact->contact_id) as $log)
                                                                
                                                                <tr>
                                                                    <th scope="row"><em class="icon ni ni-chevrons-down"></em></th>
                                                                    <td>{{ $log->status }}</td>
                                                                    <td>{{ $log->created_at->format('d-m-y H:i:s') }}</td>
                                                                    <td>{{ $log->agent_name }}</td>
                                                                </tr>

                                                                @empty
                                                                    
                                                                @endforelse
                                                            
                                                            </tbody>
                                                            </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>

                                    <td class="nk-tb-col tb-col-mb">
                                        <a href="javascript:;" title="{{ translate('Click to call') }}"
                                            onclick="MakeVoiceCall('sl-{{ $contact->id }}', null, {{ $contact->contacts->phone }}, {{ $contact->contact_id }}, {{ $campaign_id }}, true, true, 'd')">
                                            {{ $contact->contacts->phone }}
                                        </a>
                                    </td>

                                    <td class="nk-tb-col nk-tb-col-md">
                                        <a href="javascript:;" id="st-{{ $contact->id }}p"
                                        onclick="MakeVoiceCall(null, 'st-{{ $contact->id }}',
                                        {{ $contact->contacts->phone }}, {{ $contact->contact_id }},
                                        {{ $campaign_id }}, true, false, 'p')" class="btn-xs
                                        btn-{{ voice_campaign_status($campaign_id, $contact->contact_id, $contact->contacts->phone) == 'p' ? 'info' : 'secondary' }}"
                                        title="Picked">P</a>
                                        <a href="javascript:;" id="st-{{ $contact->id }}b"
                                            onclick="MakeVoiceCall(null, 'st-{{ $contact->id }}', {{ $contact->contacts->phone }}, {{ $contact->contact_id }}, {{ $campaign_id }}, true, false, 'b')"
                                            class="btn-xs btn-{{ voice_campaign_status($campaign_id, $contact->contact_id, $contact->contacts->phone) == 'b' ? 'info' : 'secondary' }}"
                                            title="Busy">B</a>
                                        <a href="javascript:;" id="st-{{ $contact->id }}s"
                                            onclick="MakeVoiceCall(null, 'st-{{ $contact->id }}', {{ $contact->contacts->phone }}, {{ $contact->contact_id }}, {{ $campaign_id }}, true, false, 's')"
                                            class="btn-xs btn-{{ voice_campaign_status($campaign_id, $contact->contact_id, $contact->contacts->phone) == 's' ? 'info' : 'secondary' }}"
                                            title="Switched Off">S</a>
                                        <a href="javascript:;" id="st-{{ $contact->id }}l"
                                            onclick="MakeVoiceCall(null, 'st-{{ $contact->id }}', {{ $contact->contacts->phone }}, {{ $contact->contact_id }}, {{ $campaign_id }}, true, false, 'l')"
                                            class="btn-xs btn-{{ voice_campaign_status($campaign_id, $contact->contact_id, $contact->contacts->phone) == 'l' ? 'info' : 'secondary' }}"
                                            title="Lead">L</a>
                                    </td>

                                    <td class="nk-tb-col nk-tb-col-md text-center">
                                        <button type="button" id="sl-{{ $contact->id }}" title="{{ translate('Click to make call') }}"
                                            onclick="MakeVoiceCall('sl-{{ $contact->id }}', null, {{ $contact->contacts->phone }}, {{ $contact->contact_id }}, {{ $campaign_id }}, true, true, 'd')"
                                            class="btn-xs btn-{{ check_voice_called($campaign_id, $contact->contact_id, $contact->contacts->phone) == false ? 'secondary' : 'success' }}">
                                            <em class="icon ni ni-call"></em>
                                        </button>

                                        <button type="button" id="slc-{{ $contact->contact_id }}" title="{{ translate('Click to send sms') }}"
                                             data-toggle="modal" data-target="#contactsms-{{ $loop->iteration }}"
                                            class="btn-xs btn-{{ check_sms_sent($campaign_id, $contact->contact_id, $contact->contacts->phone) == false ? 'secondary' : 'success' }}">
                                            <em class="icon ni ni-telegram"></em>
                                        </button>

                                        <!-- Modal Form -->
                                        <div class="modal fade" tabindex="-1" id="contactsms-{{ $loop->iteration }}">
                                            <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $contact->contacts->phone }} ⇢ {{ translate('Send SMS') }}</h4>
                                                        <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                                                            <em class="icon ni ni-cross"></em>
                                                        </a>
                                                    </div>
                                                    <div class="modal-body modal-body-lg">

                                                        <label for="{{ $contact->contact_id }}">{{ translate('Write your message here') }}</label>
                                                        <textarea id="{{ $contact->contact_id }}" class="form-control" required></textarea>  
                                                        
                                                        <div class="form-group mt-2">
                                                            <button type="submit" 
                                                                    class="btn btn-lg btn-secondary" 
                                                                    onclick="SendSMS({{ $contact->contact_id }}, {{ $campaign_id }})">
                                                                        {{ translate('Send SMS') }}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr><!-- .nk-tb-item  -->
                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div><!-- .card-preview -->
        </div>
        <div class="col-md-4">
            <div class="phone w-100">
                <div class="call-display">
                    <div class="row">
                        <div class="col agent-name">{{ Auth::user()->name }}</div>
                        <div id="TIME" class="col agent-time text-right d-none">00:00:00</div>

                        <div class="w-100"></div>

                        <div class="col agent-ext">123456</div>
                    </div>

                    <div class="call-info" id="call-info">
                        <em class="icon ni ni-user-circle call-img float-left fs-1"></em>
                        <span class="call-name" id="call-name">{{ translate('Make a call') }}</span><br>
                        <span class="call-number" id="call-number"></span>
                    </div>
                    <!-- /.call-info -->

                </div>
                <!-- /.call-display -->

                <form id="dialer" class="dial-display">
                    <input type="tel" id="phone-number" pattern="[0-9 ]+" autofocus />
                </form>

                <button id="button-call" class="ans-call rounded-0">
                    <em class="icon ni ni-call"></em>
                </button>

                <button id="button-hangup" class="end-call rounded-0">
                    <em class="icon ni ni-call"></em>
                </button>

            </div>

            <div id="log" class="log-bg h-270"></div>

            <div id="output-selection">
                <label class="d-none">{{ translate('Ringtone Devices') }}</label>
                <select id="ringtone-devices" multiple class="d-none"></select>
                <label class="d-none">{{ translate('Speaker Devices') }}</label>
                <select id="speaker-devices" class="d-none" multiple></select><br />
                <a id="get-devices" class="d-none">{{ translate('Seeing unknown devices?') }}</a>
            </div>

            <div id="call-controls">
                <div id="volume-indicators">
                    <label> {{ translate('Mic Volume') }}</label>
                    <div class="flex">
                        <em class="icon ni ni-mic"></em>
                        <div id="input-volume"></div>
                    </div>

                    <label class="mt-4">{{ translate('Speaker Volume') }}</label>
                    <div class="flex">
                        <em class="icon ni ni-headphone"></em>
                        <div id="output-volume"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="{{ route('dashboard.campaign.voice.lead') }}"
        id="dashboard_campaign_voice_lead">

    <input type="hidden" id="capability_token" value="{{ route('dialer.token') }}">
    <input type="hidden" id="twilio_send_sms" value="{{ route('dashboard.campaign.send_sms') }}">

</div>
<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('dialer/twilio.js') }}"></script>
<script src="{{ asset('dialer/jquery.js') }}"></script>
<script src="{{ asset('dialer/mobile.js') }}"></script>
<script src="{{ asset('dialer/quickstart.js') }}"></script>
<script src="{{ asset('dialer/sms.js') }}"></script>
@endsection
