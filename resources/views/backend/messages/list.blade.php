<div class="nk-chat-aside-search">
    <div class="form-group">
        <div class="form-control-wrap">
            <div class="form-icon form-icon-left">
                <em class="icon ni ni-search"></em>
            </div>
            <input type="text" class="form-control form-round" id="default-03" placeholder="{{ translate('Search by name or number') }}">
        </div>
    </div>
</div><!-- .nk-chat-aside-search -->

<div class="nk-chat-list">
    <h6 class="title overline-title-alt">
        {{ translate('Messages') }}
        <a href="javascript:;">
            <span class="badge rounded-pill bg-primary text-white" 
                  title="{{ translate('You have new message.') }}"
                  id="new_message_count"
                  onclick="location.reload()">
                  {{ new_message_found($my_number) }}
            </span>
        </a>
    </h6>

    <ul class="chat-list">
        @forelse (messages($my_number) as $key => $message)
        <li class="chat-item {{ conversationOfLastMessage($key, $my_number)['seen'] == 0 ? 'is-unread' : null }}">
            <a class="chat-link chat-open" href="{{ route('message.conversations', [$my_number, $key]) }}">
                <div class="chat-media user-avatar">
                    <span>{{ shortname(find_contact($key)->name ?? 'TM') }}</span>
                    <span class="status dot dot-lg dot-gray"></span>
                </div>
                <div class="chat-info">
                    <div class="chat-from">
                        <div class="name">{{ find_contact($key)->name ?? $key }}</div>
                        <span class="time">{{ conversationOfLastMessage($key, $my_number)['time'] }}</span>
                    </div>
                    @if (find_contact($key))
                    <small class="text-muted">{{ $key }}</small>
                    @endif
                    <div class="chat-context">
                        <div class="text">
                            <p>{{ conversationOfLastMessage($key, $my_number)['message'] }}</p>
                        </div>
                        <div class="status {{ conversationOfLastMessage($key, $my_number)['seen'] == 1 ? 'unread' : null }}">
                            <em class="icon ni ni-{{  conversationOfLastMessage($key, $my_number)['seen'] == 0 ? 'bullet-fill' : 'check-circle-fill'  }}"></em>
                        </div>
                    </div>
                </div>
            </a>
            <div class="chat-actions">
                <div class="dropdown">
                    <a href="javascript:;" class="btn btn-icon btn-sm btn-trigger dropdown-toggle"
                        data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="{{ route('message.delete', [$my_number, $key]) }}">Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li><!-- .chat-item -->
        @empty
            
        @endforelse
    </ul><!-- .chat-list -->
</div><!-- .nk-chat-list -->