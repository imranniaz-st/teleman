@extends('backend.layouts.master')

@section('title')
    {{ $department == null ? translate('Web Dialer') : translate('Department of') . ' ' . get_department($department)->name}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('dialer/site.css') }}">
    <link rel="stylesheet" href="{{ asset('dialer/mobile.css') }}">
@endsection
    
@section('content')

<div class="nk-block nk-block-lg">

    @if (env('DEMO') ==  'YES')

    <div class="card card-preview">
        <div class="card-inner">
            <p>{{ ('This is a demo trial account, only verified number can make calls. Try this number +8801533149024 to check dialer. Others number will not make calls. Premium account can call to any number based on your GEO LOCATION.') }}</p>
            <p>{{ ('Dialer setup tutorial video is here: ') }} <a href="https://www.youtube.com/channel/UCaH6hs3fW33X_6DJgFJBsvA" target="_blank"><b>{{ translate('The Code Studio Youtube Channel') }}</b></a> </p>
            <p>{{ translate('If the dialer showing verification failed that means my twilio account balance is finished. Do not fade out, lots of users making calls, the balance can be finished. Thanks') }}</p>
        </div>
    </div>
        
    @endif

    @can('everyone')
        
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">

                @if ($department != null)
                
                
                @forelse (outbound_department($department)->providers as $provider)
                    <li class="preview-item">
                        <a href="{{ route('dashboard.provider.set.default', [$provider->provider_id, Str::slug(provider_name($provider->provider_id))]) }}" 
                        class="btn btn-{{ getProviderById($provider->provider_id)->ivr == 1 ? 'info' : 'secondary' }}"
                        title="{{ translate('Click to set as default') }}">
                        <em class="icon ni ni-call mr-2"></em>
                        {{ getProviderById($provider->provider_id)->phone }}
                        </a>
                    </li>
                @empty

                <li>
                    {{ translate('No provider available for this department.') }}
                </li>

                @endforelse

                <li class="preview-item">
                    <button type="button"
                       class="btn btn-warning"
                       title="{{ translate('Click to reset call session') }}"
                       onclick="resetCallSession()">
                       <em class="icon ni ni-repeat-v mr-2"></em>
                       {{ translate('Reset Call Session') }}
                    </button>
                </li>

                @else

                <li class="preview-item text-danger">
                       {{ translate('Please select an outbound department.') }}
                </li>
                    
                @endif
                

            </ul>
        </div>
    </div><!-- .card-preview -->

    @endcan


    <div class="card card-preview">
        <div class="card-inner">
            @if ($department != null)
            {{-- Mobile UI --}}
            <div class="row">
                    <div class="col-md-6">
                        <div class="phone">
                            <div class="call-display">
                            <div class="row">
                                <div class="col agent-name">{{ get_user_identity(auth()->id()) }}</div>
                                <div id="TIME" class="col agent-time text-right d-none">00:00:00</div>

                                <div class="w-100"></div>

                                <div class="col agent-ext d-none">{{ translate('123456') }}</div>
                            </div>

                            <div class="call-info" id="call-info">
                                <em class="icon ni ni-user-circle call-img float-left fs-1"></em>
                                <span class="call-name" id="call-name">{{ translate('Input a number to make call') }}</span><br>
                                <span class="call-number" id="call-number"></span>
                            </div>
                            <!-- /.call-info -->

                            </div>
                            <!-- /.call-display -->

                            <form id="dialer" class="dial-display">
                                <input type="tel" id="phone-number" pattern="[0-9 ]+" autofocus />
                                <input type="reset" class="fs-2" value="&#8635;">
                            </form>

                            <div class="grid">
                            <button value="1">{{ translate('1') }}</button>
                            <button value="2">{{ translate('2') }} <span>{{ translate('ABC') }}</span></button>
                            <button value="3">{{ translate('3') }} <span>{{ translate('DEF') }}</span></button>
                            <button value="4">{{ translate('4') }} <span>{{ translate('GHI') }}</span></button>
                            <button value="5">{{ translate('5') }} <span>{{ translate('JKL') }}</span></button>
                            <button value="6">{{ translate('6') }} <span>{{ translate('MNO') }}</span></button>
                            <button value="7">{{ translate('7') }} <span>{{ translate('PQRS') }}</span></button>
                            <button value="8">{{ translate('8') }} <span>{{ translate('TUV') }}</span></button>
                            <button value="9">{{ translate('9') }} <span>{{ translate('WXYZ') }}</span></button>
                            <button></button>
                            <button value="0">{{ translate('0') }}</button>
                            <button></button>
                            </div>
                            <!-- /.grid -->

                            <button id="button-call" class="ans-call">
                                <em class="icon ni ni-call"></em>
                            </button>

                            <button id="button-hangup" class="end-call">
                                <em class="icon ni ni-call"></em>
                            </button>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="log" class="log-bg"></div>

                        <div id="output-selection">
                            <label class="d-none">{{ translate('Ringtone Devices') }}</label>
                            <select id="ringtone-devices" multiple class="d-none"></select>
                            <label class="d-none">{{ translate('Speaker Devices') }}</label>
                            <select id="speaker-devices" class="d-none" multiple></select><br/>
                            <a id="get-devices" class="d-none">{{ translate('Seeing unknown devices?') }}</a>
                        </div>

                        <div id="call-controls">
                            <div id="volume-indicators">
                                <label> {{ translate('Mic Volume') }}</label>
                                <div class="flex">
                                    <em class="icon ni ni-mic"></em><div id="input-volume"></div>
                                </div>

                                <label class="mt-4">{{ translate('Speaker Volume') }}</label>
                                <div class="flex">
                                    <em class="icon ni ni-headphone"></em><div id="output-volume"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            {{-- Mobile UI::ENDS --}}
            @else
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div>
                    {{ lordicon('ritcuqlt', 'rfgxevig', 'loop', '000000', '7789fb', 250) }}
                </div>
                <h1 class="lead">{{ translate('Please select an outbound department.') }}</h1>
            </div>
            @endif

        </div>
    </div>

<input type="hidden" id="capability_token" value="{{ route('dialer.token') }}">
<input type="hidden" id="create_call_history_url" value="{{ route('create.call.hostory') }}">


{{-- log message --}}
<input type="hidden" id="log_connecting" value="{{ translate('Connecting') }}......">
<input type="hidden" id="verifying_identification" value="{{ translate('Verifying identification') }}......">
<input type="hidden" id="identity" value="{{ translate('identity') }}......">
<input type="hidden" id="device_is_ready_to_make_calls" value="{{ translate('Device is ready to make calls') }}.">
<input type="hidden" id="device_error" value="{{ translate('Device Error') }}.">
<input type="hidden" id="successfully_established_call" value="{{ translate('Successfully established call') }}!">
<input type="hidden" id="call_ended" value="{{ translate('Call ended') }}!">
<input type="hidden" id="call_ended_at" value="{{ translate('Call ended at') }}!">
<input type="hidden" id="something_went_wrong" value="{{ translate('Something went wrong!') }}">
<input type="hidden" id="no_call_to_hangup" value="{{ translate('No call to hangup') }}">
<input type="hidden" id="verification_failed" value="{{ translate('Verification failed!') }}">
<input type="hidden" id="please_enter_a_valid_phone_number" value="{{ translate('Please enter a valid phone number') }}">


</div>
    
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('dialer/twilio.js') }}"></script>
<script src="{{ asset('dialer/jquery.js') }}"></script>
<script src="{{ asset('dialer/mobile.js') }}"></script>
<script src="{{ asset('dialer/quickstart.js') }}"></script>
@endsection