@extends('backend.layouts.master')

@section('title')
{{ translate('Messages') }}
@endsection

@section('css')

<style>
    .nk-wrap {
        padding: 0 !important;
    }

    .nk-sidebar {
        display: none !important;
    }
</style>

@endsection

@section('content')

<div class="nk-chat">
    <div class="nk-chat-aside">
        @includeWhen(true, 'backend.messages.compose', ['my_number' => $my_number])
        <div class="nk-chat-aside-body" data-simplebar>
            @includeWhen(true, 'backend.messages.list', ['my_number' => $my_number])
        </div>
    </div><!-- .nk-chat-aside -->

    @if ($user_number != null)
        
    <div class="nk-chat-body profile-shown">
        <div class="nk-chat-head">
            <ul class="nk-chat-head-info">
                <li class="nk-chat-body-close">
                    <a href="#" class="btn btn-icon btn-trigger nk-chat-hide ml-n1"><em
                            class="icon ni ni-arrow-left"></em></a>
                </li>
                <li class="nk-chat-head-user">
                    <div class="user-card">
                        <div class="user-avatar bg-purple">
                            <span>{{ shortname(find_contact($user_number)->name ?? 'TM') }}</span>
                        </div>
                        <div class="user-info">
                            <div class="lead-text">
                                @if (find_contact($user_number))
                                    {{ find_contact($user_number)->name }}
                                @else
                                    {{ conversationOfLastMessage($user_number, $my_number)['user_number'] }}
                                @endif
                            </div>
                            <div class="sub-text"><span class="d-none d-sm-inline mr-1">{{ translate('Active') }} </span> {{ conversationOfLastMessage($user_number, $my_number)['activeSince'] }}</div>
                        </div>
                    </div>
                </li>
            </ul>

            @if (teleman_config('dashboard_ui') == 'CONTAINER')
            <ul class="nk-chat-head-tools">
                <li>
                    <a href="#" type="button" class="btn btn-icon btn-trigger text-primary" data-toggle="modal" data-target="#modalUserDetails">
                        <em class="icon ni ni-user-circle"></em>
                    </a>
                </li>
            </ul>
            @endif
            
            <div class="nk-chat-head-search">
                <div class="form-group">
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-left">
                            <em class="icon ni ni-search"></em>
                        </div>
                        <input type="text" class="form-control form-round" id="chat-search"
                            placeholder="Search in Conversation">
                    </div>
                </div>
            </div><!-- .nk-chat-head-search -->
        </div><!-- .nk-chat-head -->

        <div class="nk-chat-panel">
            @forelse (conversations($user_number, $my_number) as $conversation)
            
            <div class="chat is-{{ $user_number == $conversation->sender ? 'you' : 'me' }}">
                @if ($user_number == $conversation->sender)
                <div class="chat-avatar">
                    <div class="user-avatar bg-purple">
                        <span>{{ shortname(find_contact($user_number)->name ?? 'TM') }}</span>
                    </div>
                </div>
                @endif
                
                <div class="chat-content">
                    <div class="chat-bubbles">
                        <div class="chat-bubble">
                            <div class="chat-msg"> 
                                @if ($conversation->content != null)
                                    {{ $conversation->content }} 
                                @else
                                <img src="{{ asset($conversation->mms) }} " alt="" class="w-100">
                                @endif
                            </div>
                            <ul class="chat-msg-more">
                                <li class="d-none d-sm-block"><a href="javascript:;" class="btn btn-icon btn-sm btn-trigger"><em
                                            class="icon ni ni-reply-fill"></em></a></li>
                            </ul>
                        </div>
                    </div>
                    <ul class="chat-meta">
                        <li>{{ time_formatter($conversation->sent_at)->format('d F, Y h:i A') }}</li>
                    </ul>
                </div>
            </div><!-- .chat -->

            @empty
                
            @endforelse

        </div><!-- .nk-chat-panel -->

        <div class="nk-chat-editor">

            <div class="nk-chat-editor-upload  ml-n1">
                <a href="javascript:;" class="btn btn-sm btn-icon btn-trigger text-primary toggle-opt" data-target="chat-upload"><em class="icon ni ni-plus-circle-fill"></em></a>
                <div class="chat-upload-option" data-content="chat-upload">
                    <ul class="">
                        <li><a href="javascript:;" id="file-upload-icon"><em class="icon ni ni-img-fill"></em></a></li>
                        <!-- Hidden input element for file upload -->
                        <input type="file" id="file-input" class="d-none">
                    </ul>
                </div>
            </div>
       
            <div class="nk-chat-editor-form">

                <!-- Image preview container -->
                <div id="image-preview-container" class="pl-45">
                    <img id="image-preview" src="" class="d-none w-10" alt="Image Preview">
                </div>

                <div class="form-control-wrap">
                    <textarea class="form-control form-control-simple no-resize" 
                                rows="1" 
                                id="content"
                                name="content"
                                placeholder="{{ translate('Type your message') }}..."></textarea>
                </div>
            </div>
            <ul class="nk-chat-editor-tools g-2">
                <li>
                    <button class="btn btn-round btn-primary btn-icon" type="submit" onclick="sendMessage()">
                        <em class="icon ni ni-send-alt"></em>
                    </button>
                </li>
            </ul>
         
        </div><!-- .nk-chat-editor -->
        @if (teleman_config('dashboard_ui') == 'EXTENDED')
            @includeWhen(true, 'backend.messages.profile', ['user_number' => $user_number, 'my_number' => $my_number])
        @endif

    </div><!-- .nk-chat-body -->

    @if (teleman_config('dashboard_ui') == 'CONTAINER')
    <!-- Modal Content Code -->
        <div class="modal fade" tabindex="-1" id="modalUserDetails">    
            <div class="modal-dialog" role="document">        
                <div class="modal-content">            
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">                
                        <em class="icon ni ni-cross"></em>            
                    </a>            
                    <div class="modal-header">                
                        <h5 class="modal-title">{{ translate('User Details') }}</h5>            
                    </div>            
                    <div class="modal-body modal-body-lg">                
                        @includeWhen(true, 'backend.messages.profile', ['user_number' => $user_number, 'my_number' => $my_number])
                    </div>       
                </div>    
            </div>
        </div>
    @endif

    @else

    <div class="container">
        <div class="d-flex justify-content-center align-items-center flex-column" style="height: 90vh;">
            <div>
                {{ lordicon('ritcuqlt', 'zpxybbhl', 'loop', '000000', '7789fb', 250) }}
            </div>
            <h1 class="lead">{{ translate('Click any message to see details') }}</h1>
        </div>
    </div>

    @endif
    
</div><!-- .nk-chat -->

@includeWhen(true, 'backend.contacts.create', ['user_number' => $user_number])

{{-- AJAX HIDDEN VALUES::START --}}
<input type="hidden" id="message_show_url" value="{{ route('messages.show', [$my_number, $user_number]) }}">
<input type="hidden" id="user_number" value="{{ $user_number }}">
<input type="hidden" id="my_number" value="{{ $my_number }}">
<input type="hidden" id="user_name" value="{{ shortname(find_contact($user_number)->name ?? 'TM') }}">

{{-- LISTS --}}
<input type="hidden" id="messageajaxfetch" value="{{ route('message.ajax.fetch', $my_number) }}">

{{-- Send message --}}
<input type="hidden" id="send_message_url" value="{{ route('messages.send', [$user_number, $my_number]) }}">
<input type="hidden" id="my_url" value="{{ url('/') }}">

{{-- AJAX HIDDEN VALUES::ENDS --}}

@endsection

@section('js')
<script src="{{ asset('js\moment.js') }}"></script>
<script>
    "use strict"

    $(document).ready(function() {
        $('.nk-chat-panel').animate({
            scrollTop: $('.nk-chat-panel').prop("scrollHeight")
        }, 500);
    });

    const my_url = $('#my_url').val();
    
    function checkProtocol(url) {
        if (url?.startsWith("http://")) {
          return url;
        } else if (url?.startsWith("https://")) {
          return url;
        } else {
          return my_url + '/' + url;
        }
      }

    // Define a function to fetch the latest messages from the server
    function fetchMessages() {

        var url = $('#message_show_url').val();
        var user_number = $('#user_number').val();
        var my_number = $('#my_number').val();
        var user_name = $('#user_name').val();

        $.ajax({
            url: url,
            success: function(messages) {
                var chatPanel = $('.nk-chat-panel');
                chatPanel.empty();

                $.each(messages, function(i, message) {
                    var chat = $('<div/>', {
                        class: 'chat is-' + (message.sender == user_number ? 'you' : 'me')
                    });

                    if (message.sender == user_number) {
                        var chatAvatar = $('<div/>', {
                            class: 'chat-avatar'
                        }).appendTo(chat);

                        var userAvatar = $('<div/>', {
                            class: 'user-avatar bg-purple'
                        }).appendTo(chatAvatar);

                        $('<span/>', {
                            text: user_name
                        }).appendTo(userAvatar);
                    }

                    var chatContent = $('<div/>', {
                        class: 'chat-content'
                    }).appendTo(chat);

                    var chatBubbles = $('<div/>', {
                        class: 'chat-bubbles'
                    }).appendTo(chatContent);

                    var chatBubble = $('<div/>', {
                        class: 'chat-bubble'
                    }).appendTo(chatBubbles);

                    if (message.mms != null) {
                        var mmsImage = $('<img/>', {
                            src: checkProtocol(message.mms),
                            alt: '',
                            class: 'w-100'
                        }).appendTo(chatBubble);
                    }else{
                        $('<div/>', {
                            class: 'chat-msg',
                            text: message.content
                        }).appendTo(chatBubble);
                    }

                    var chatMsgMore = $('<ul/>', {
                        class: 'chat-msg-more'
                    }).appendTo(chatBubble);

                    $('<li/>', {
                        class: 'd-none d-sm-block'
                    }).appendTo(chatMsgMore);

                    $('<a/>', {
                        href: 'javascript:;',
                        class: 'btn btn-icon btn-sm btn-trigger'
                    }).appendTo(chatMsgMore).html('<em class="icon ni ni-reply-fill"></em>');

                    var chatMeta = $('<ul/>', {
                        class: 'chat-meta'
                    }).appendTo(chatContent);

                    $('<li/>', {
                        text: moment(message.sent_at, "YYYY-MM-DD HH:mm:ss").format("DD MMMM, YYYY hh:mm A")
                    }).appendTo(chatMeta);

                    chat.appendTo(chatPanel);
                });
                refreshChatAside();
            },

            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

        
    }

    // get new message count
    function fetchMessageLists() {
        var url = $('#messageajaxfetch').val();
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response > 0) {
                    // update the chat list with new messages
                    $('#new_message_count').text(response);
                    refreshChatAside();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // send message functionality
    function sendMessage() {
        // Prevent default form submission
        event.preventDefault();
        var url = $('#send_message_url').val();
        var content = $('#content').val();
        // Get references to the image preview elements
        const imagePreviewContainer = $('#image-preview-container');
        const imagePreview = $('#image-preview');
        var chatPanel = $('.nk-chat-panel');

        // MMS
        const fileInput = $('#file-input');
        const selectedFile = fileInput[0].files[0];
        var formData = new FormData();

        if (selectedFile) {
            formData.append('file', selectedFile);
        }

        formData.append('content', content);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    fetchMessages();
                    // Clear the image preview if no file is selected
                    imagePreview.attr('src', '');
                    imagePreview.addClass('d-none');
                    $('#content').val(''); // Clear textarea content
                    $('#content').show();
                    fileInput.val(''); // Clear file input
                    $('.toggle-opt').removeClass('active');
                    toastr.success('Message sent.');
                    chatPanel.animate({
                        scrollTop: chatPanel.prop("scrollHeight")
                    }, 500);
                    sentMessageSound.play();
                    refreshChatAside();
                } else {
                    toastr.error('Error sending message.');
                }
            },
            error: function () {
                toastr.error('Error sending message.');
            }
        });
    }

    $(document).ready(function() {
        // Search functionality
        $('#default-03').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.chat-list > li').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // File Upload For MMS ---------------------------
        // Get references to the icon and input elements
        const fileUploadIcon = $('#file-upload-icon');
        const fileInput = $('#file-input');
        
        // Get references to the image preview elements
        const imagePreviewContainer = $('#image-preview-container');
        const imagePreview = $('#image-preview');

        // Attach a click event listener to the icon
        fileUploadIcon.on('click', function() {
            // Trigger the click event on the hidden file input element
            fileInput.click();
        });

        // Listen for file selection in the input
        fileInput.on('change', function() {
            // Get the selected file
            const selectedFile = fileInput[0].files[0];

            if (selectedFile) {
                // Set the image preview source
                const fileReader = new FileReader();
                fileReader.onload = function(e) {
                    imagePreview.attr('src', e.target.result);
                    imagePreview.removeClass('d-none');
                    $('#content').hide();
                    $('#content').val('');
                };
                fileReader.readAsDataURL(selectedFile);
            } else {
                // Clear the image preview if no file is selected
                imagePreview.attr('src', '');
                imagePreview.addClass('d-none');
                $('#content').val('');
                $('#content').show();
            }
        });

    });

    function refreshChatAside() {
        var chatAsideBody = $('.nk-chat-aside-body');
    
        $.ajax({
            url: '{{ route("refresh-messages", $my_number) }}',
            success: function (data) {
                chatAsideBody.html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }

    // Call the fetchMessages function every 10 seconds
    setInterval(fetchMessages, 5000);
    setInterval(fetchMessageLists, 5000);
</script>
@endsection
