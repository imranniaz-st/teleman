<div class="nk-chat-aside-head">
    <div class="nk-chat-aside-user">
        <div class="dropdown">
            <div class="user-avatar">
                <img src="{{ avatar(auth()->user()->name) }}" alt="{{ auth()->user()->name }}">
            </div>
            <div class="title">
                <a href="{{ route('message.index') }}">
                    {{ translate('Messages') }}
                </a>
            </div>
        </div>
    </div><!-- .nk-chat-aside-user -->
    <ul class="nk-chat-aside-tools g-2">
        <li>
            <a href="javascript:;" class="btn btn-round btn-icon btn-light" data-toggle="modal" data-target="#modalCompose">
                <em class="icon ni ni-edit-alt-fill"></em>
            </a>
        </li>
    </ul><!-- .nk-chat-aside-tools -->
</div><!-- .nk-chat-aside-head -->

<div class="modal fade" tabindex="-1" id="modalCompose">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Send New Message') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('messages.compose_new_message', [$my_number]) }}" class="form-validate is-alter"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">

                        <div class="col-lg-12">
                            <label class="form-label" for="phone_number">{{ translate('Phone Number') }}
                                    </label>
                            <div class="form-control-wrap">
                                    <input class="form-control" id="phone_number" name="new_phone" placeholder="Write you number here">
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">

                        <div class="col-lg-12">
                            <label class="form-label" for="message_content">{{ translate('Contacts') }}
                                    *</label>
                            <select class="form-select" single="single" data-placeholder="Select Contact" name="phone">
                                <option value="">{{ translate('Select Contact') }}</option>
                                @forelse (allContacts() as $contact)
                                    <option value="{{ $contact->phone }}">{{ Str::upper($contact->name) }}({{ $contact->phone }})</option>
                                @empty
                                    
                                @endforelse
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="message_content">{{ translate('Message') }}
                                    *</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" id="message_content" name="content" placeholder="Write you message here" required=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="form-group mt-2">
                                <button type="submit"
                                    class="btn btn-lg btn-secondary">{{ translate('Send Message') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>