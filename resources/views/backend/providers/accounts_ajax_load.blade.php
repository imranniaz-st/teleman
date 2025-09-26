
        @forelse (getVoiceServerWiseList() as $provider)

        @php
            try {
                $check_twilio = fetch_twilio_account($provider->user_id, $provider->account_sid);
            } catch (\Exception $e) {
                $check_twilio = false;
            }
        @endphp

        <div class="col-sm-6 col-xl-4 col-md-4">
            <div class="card card-bordered h-100">
                <div class="card-inner">
                    <div class="project">
                        <div class="project-head"><a href="javascript:;" class="project-title">
                                <div class="user-avatar sq bg-purple"><span>{{ $loop->iteration }}</span></div>
                                <div class="project-info">
                                    <h6 class="title">

                                        @if (you($provider->user_id))
                                            {{ translate('YOU') }}                                        
                                        @else
                                            {{ Str::upper($provider->user->name) }}
                                        @endif
                                    </h6>
                                    <p class="fw-bold">{{ $provider->user->email }}</p>
                                </div>
                            </a>
                        </div>

                        @if ($check_twilio)
                
                        <div class="project-details">
                            <p>{{ translate('Phone Number') }}: <span class="fw-bold ml-1">
                                {{ $provider->phone }}
                            </span></p>
                            <p>{{ translate('Friendly name') }}: <span class="fw-bold ml-1">
                                {{ $friendly_name ?? 'N/A' }}
                            </span></p>
                            <p>{{ translate('Balance') }}: <span class="text-success fw-bold ml-2">
                                {{ twilio_balance($provider->user_id, $provider->account_sid) }}
                                </span>
                            </p>
                            <p>{{ translate('Status') }}: 
                                <span class="text-white 
                                badge bg-{{ account_data($provider->user_id, $provider->account_sid)['status'] == 'active' ? 'success' : 'danger' }} 
                                ml-2">
                                {{ account_data($provider->user_id, $provider->account_sid)['status'] }}
                            </span>
                            </p>
                        </div>
                        <div class="project-progress">
                            <div class="project-progress-details">
                                <div class="project-progress-task">
                                    <em class="icon ni ni-clock"></em><span>
                                        {{ $provider->hourly_quota }} {{ translate('Hourly Quota') }}</span></div>
                                <div class="project-progress-percent">
                                    {{ hourly_quota_left_in_percentage($provider->user_id, $provider->id) }}% {{ translate('left') }}
                                </div>
                            </div>
                            <div class="progress progress-pill progress-md bg-light">
                                <div class="progress-bar" 
                                     data-progress="{{ hourly_quota_left_in_percentage($provider->user_id, $provider->id) }}" 
                                     style="width: {{ hourly_quota_left_in_percentage($provider->user_id, $provider->id) }}%;">
                                </div>
                            </div>
                        </div>
                        <div class="project-meta">
                            <ul class="project-users g-1">
                                <li>
                                    <a href="{{ route('dashboard.provider.call.export', $provider->account_sid) }}">
                                        <span class="badge bg-outline-primary">{{ translate('Export CSV') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('dashboard.provider.call.logs', $provider->account_sid) }}">
                                        <span class="badge bg-outline-primary">{{ translate('Call logs') }}</span>
                                    </a>
                                </li>
                            </ul>
                            <span class="badge badge-dim bg-info">
                                <em class="icon ni ni-clock"></em>
                                <span>{{ hourly_quota_left($provider->user_id, $provider->account_sid) }} {{ translate('Q. Left') }}</span>
                            </span>
                        </div>

                        @else

                        <p>
                            {{ translate('No Twilio Account Found') }}
                            {{ translate('Please visit') }} <a href="https://www.twilio.com" target="_blank" class="text-danger fw-bold">{{ translate('Twilio') }}</a> {{ translate('to create a new account or update account.') }}
                            <br>
                            <br>
                            {{ translate('Without the twilio connection this provider can not run campaign.') }}
                        </p>

                         <div class="project-meta mt-2">
                            <ul class="project-users g-1">
                                <li>
                                    <a href="{{ route('dashboard.provider.edit', [ $provider->id, Str::slug($provider->provider_name) ]) }}">
                                        <span class="badge bg-outline-primary">{{ translate('Fix Connection') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        @endif

                    </div>
                </div>
            </div>
        </div>

        @empty

        @endforelse


<script src="{{ asset('backend/js/loader.js') }}"></script>
