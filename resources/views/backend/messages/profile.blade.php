<div class="@if(teleman_config('dashboard_ui') == 'EXTENDED') nk-chat-profile visible @endif" data-simplebar>
            <div class="user-card user-card-s2 my-4">
                <div class="user-avatar md bg-purple">
                    <span>{{ shortname(find_contact($user_number)->name ?? 'TM') }}</span>
                </div>
                <div class="user-info">
                    @if (find_contact($user_number))
                        <div class="lead-text">{{ find_contact($user_number)->name }}</div>
                    @endif
                    <h5>{{ $user_number }}</h5>
                    <span class="sub-text">{{ translate('Active') }} {{ conversationOfLastMessage($user_number, $my_number)['activeSince'] }}</span>
                </div>
                {{-- <div class="user-card-menu dropdown">
                    <a href="#" class="btn btn-icon btn-sm btn-trigger dropdown-toggle" data-toggle="dropdown"><em
                            class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="link-list-opt no-bdr">
                            <li><a href="#"><em class="icon ni ni-eye"></em><span>{{ translate('View Profile') }}</span></a></li>
                        </ul>
                    </div>
                </div> --}}
            </div>
            <div class="chat-profile">
                <div class="chat-profile-group">
                    <a href="javascript:;" class="chat-profile-head" data-toggle="collapse" data-target="#chat-options">
                        <h6 class="title overline-title">{{ translate('Details') }}</h6>
                        <span class="indicator-icon"><em class="icon ni ni-chevron-down"></em></span>
                    </a>
                    <div class="chat-profile-body collapse show" id="chat-options">
                        <div class="chat-profile-body-inner">
                            <ul class="chat-profile-options">
                                <li>
                                    @if (find_contact($user_number))
                                    @php
                                        $contact = find_contact($user_number);
                                    @endphp
                                        <form action="{{ route('dashboard.contact.update', [$contact->id, Str::slug($contact->name)]) }}" class="form-validate is-alter"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="row g-3 align-center mt-1">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="account_sid">{{ translate('Full Name') }}
                                                            *</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="account_sid" name="name"
                                                                value="{{ $contact->name }}" placeholder="Full Name"
                                                                required="">
                                                            @error('name')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row g-3 align-center mt-1">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="phone">{{ translate('Phone') }}
                                                            *</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <div class="form-group">
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" id="phone" name="phone"
                                                                value="{{ $contact->phone }}" placeholder="Phone Number"
                                                                required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-center mt-1">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">{{ translate('Country') }} *</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <select class="form-select" single="single" data-placeholder="Select Country"
                                                        name="country">
                                                        @foreach(getCountry() as $country)
                                                            <option value="{{ Str::lower($country) }}" {{ Str::lower($contact->country) == Str::lower($country) ? 'selected' : null }}>{{ Str::upper($country) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="row g-3 align-center mt-1">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">{{ translate('Gender') }} *</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <select class="form-select" single="single" data-placeholder="Select Gender" name="gender">
                                                        <option value="male" {{ $contact->gender == 'male' ? 'selected' : null }}>{{ Str::upper('Male') }}</option>
                                                        <option value="female" {{ $contact->gender == 'female' ? 'selected' : null }}>{{ Str::upper('Female') }}</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="row g-3 align-center mt-1">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="name">{{ translate('Profession') }} *</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <select class="form-select" single="single" data-placeholder="Select Profession"
                                                        name="profession">
                                                        @foreach(professionList() as $profession)
                                                            <option value="{{ Str::lower($profession) }}" {{ Str::lower($contact->profession) == Str::lower($profession) ? 'selected' : null }}>{{ Str::upper($profession) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="row g-3 align-center mt-1">
                                                <div class="col-lg-5">
                                                    <div class="form-group">
                                                        <label class="form-label" for="dob">{{ translate('Date Of Birth') }}
                                                            *</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control date-picker" name="dob" id="dob" value="{{ $contact->dob }}"
                                                        placeholder="Date of birth">
                                                </div>

                                            </div>

                                            @if(allGroups()->count() > 0)

                                                <div class="row g-3 align-center mt-1">
                                                    <div class="col-lg-5">
                                                        <div class="form-group">
                                                            <label class="form-label"
                                                                for="name">{{ translate('Groups') }}</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-7">
                                                        <select class="form-select" multiple="multiple" data-placeholder="Select Groups"
                                                            name="groups_ids[]">
                                                            @foreach(allGroups() as $group)
                                                                <option value="{{ $group->id }}" 
                                                                    @foreach ($contact->group_contacts as $groups_id)
                                                                        {{ $group->id == $groups_id->group_id ? 'selected' : null }}
                                                                    @endforeach
                                                                    >{{ Str::upper($group->name) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>

                                            @endif

                                            <div class="row g-3">
                                                <div class="col-lg-7 offset-lg-5">
                                                    <div class="form-group mt-2">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-secondary">{{ translate('Update') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                    <a class="chat-option-link" href="javascript:;" data-toggle="modal" data-target="#modalForm"><em
                                    class="icon icon-circle bg-light ni ni-edit-alt"></em><span
                                    class="lead-text">
                                        {{ translate('Create New Contact') }}
                                    </span></a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .chat-profile-group -->
                {{-- <div class="chat-profile-group">
                    <a href="#" class="chat-profile-head" data-toggle="collapse" data-target="#chat-settings">
                        <h6 class="title overline-title">{{ translate('Settings') }}</h6>
                        <span class="indicator-icon"><em class="icon ni ni-chevron-down"></em></span>
                    </a>
                    <div class="chat-profile-body collapse show" id="chat-settings">
                        <div class="chat-profile-body-inner">
                            <ul class="chat-profile-settings">
                                <li>
                                    <div class="custom-control custom-control-sm custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch2">
                                        <label class="custom-control-label" for="customSwitch2">{{ translate('Notifications') }}</label>
                                    </div>
                                </li>
                                <li>
                                    <a class="chat-option-link" href="#">
                                        <em class="icon icon-circle bg-light ni ni-alert-fill"></em>
                                        <div>
                                            <span class="lead-text">{{ translate('Something Wrong') }}</span>
                                            <span class="sub-text">{{ translate('Give feedback and report conversion.') }}</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
                <!-- .chat-profile-group -->
            </div> <!-- .chat-profile -->
        </div><!-- .nk-chat-profile -->

        