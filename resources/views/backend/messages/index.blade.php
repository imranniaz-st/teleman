@extends('backend.layouts.master')

@section('title')
{{ translate('Messages') }}
@endsection

@section('css')

@endsection

@section('content')

@forelse (getVoiceServerWiseList() as $provider)
    <div class="col-sm-6 col-lg-4 col-xxl-3">
        <div class="card card-bordered h-100">
            <div class="card-inner">
                <div class="project">
                    <div class="project-head">
                        <a href="{{ route('message.conversations', $provider->phone) }}" class="project-title">
                            <div class="user-avatar sq bg-purple"><span>{{ $loop->iteration }}</span></div>
                            <div class="project-info">
                                <h6 class="title">{{ $provider->phone }}</h6>
                                <span class="sub-text">{{ $provider->provider_name }}</span>
                            </div>
                        </a>
                    </div>
                    <div class="project-details">
                        <p>{{ translate('You have') }} {{ count_total_unseen_messages($provider->phone) }} {{ translate(Str::plural('message', count_total_unseen_messages($provider->phone)) . ' that are still unopened.') }}.</p>
                    </div>
                    <div class="project-progress">
                        <div class="project-progress-details">
                            <div class="project-progress-task"><em class="icon ni ni-chat"></em><span>{{ count_total_messages($provider->phone) }} {{ translate(Str::plural('message', count_total_messages($provider->phone))) }}</span></div>
                        </div>
                        <div class="progress progress-pill progress-md bg-light">
                            <div class="progress-bar" data-progress="100"></div>
                        </div>
                    </div>
                    <div class="project-meta">
                        <ul class="project-users g-1">
                            @forelse (messages($provider->phone) as $key => $user)
                                @if ($loop->iteration <= 4)
                                    <li>
                                        <div class="user-avatar sm bg-blue">
                                            <img src="{{ avatar(find_contact($key) != null ? find_contact($key)->name : 'TM', 2) }}" 
                                                alt="{{ find_contact($key)->name ?? $key }}" 
                                                title="{{ find_contact($key)->name ?? $key }}">
                                        </div>
                                    </li>
                                @endif
                            @empty

                            @endforelse

                            @if (count(messages($provider->phone)) > 4)

                            <li>
                                <div class="user-avatar bg-light sm"><span>
                                    +{{ count(messages($provider->phone)) - 4 }}
                                </span></div>
                            </li>
                                
                            @endif
                            
                            
                        </ul>
                        <span class="badge badge-dim badge-warning">
                            <em class="icon ni ni-clock"></em>
                            <a href="{{ route('message.conversations', $provider->phone) }}" class="btn-sm btn-secondary">
                                    {{ translate('OPEN') }}
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty

@endforelse

@endsection

@section('js')

@endsection
