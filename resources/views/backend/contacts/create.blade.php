<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ translate('Add New Contact') }}</h4>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body modal-body-lg">
                <form action="{{ route('dashboard.contact.store') }}" class="form-validate is-alter"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="account_sid">{{ translate('Full Name') }}
                                    *</label>
                                <span
                                    class="form-note">{{ translate('Specify the full name of the contact') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="account_sid" name="name"
                                        value="{{ old('name') }}" placeholder="Full Name" required="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="phone">{{ translate('Phone Number') }}
                                    *</label>
                                <span
                                    class="form-note">{{ translate('Specify the phone number') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', $user_number ?? null) }}" placeholder="Phone Number"
                                        required="">
                                    <small>{{ translate('Please provide country code with the phone number') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Country') }} *</label>
                                <span class="form-note">{{ translate('Specify the country') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select Country"
                                name="country">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach(getCountry() as $key => $country)
                                    <option value="{{ Str::lower($country) }}">
                                        {{ Str::upper($country) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Gender') }} *</label>
                                <span
                                    class="form-note">{{ translate('Specify the contact gender') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select Gender" name="gender">
                                <option value="">{{ translate('Select Gender') }}</option>
                                <option value="male">{{ Str::upper('Male') }}</option>
                                <option value="female">{{ Str::upper('Female') }}</option>
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="name">{{ translate('Profession') }} *</label>
                                <span
                                    class="form-note">{{ translate('Specify the contact profession') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <select class="form-select" single="single" data-placeholder="Select Profession"
                                name="profession">
                                <option value="">{{ translate('Select Profession') }}</option>
                                @foreach(professionList() as $profession)
                                    <option value="{{ Str::lower($profession) }}">{{ Str::upper($profession) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 align-center">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label" for="dob">{{ translate('Date Of Birth') }}
                                    *</label>
                                <span
                                    class="form-note">{{ translate('Specify the name of the provider name') }}.</span>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <input type="text" class="form-control date-picker" name="dob" id="dob"
                                placeholder="Date of birth">
                        </div>

                    </div>

                    @if(allGroups()->count() > 0)

                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label"
                                        for="name">{{ translate('Assign To Groups') }}</label>
                                    <span
                                        class="form-note">{{ translate('Specify the group of the contact') }}.</span>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <select class="form-select" multiple="multiple" data-placeholder="Select Groups"
                                    name="groups_ids[]">
                                    @foreach(allGroups() as $group)
                                        <option value="{{ $group->id }}">{{ Str::upper($group->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    @endif

                    <div class="row g-3">
                        <div class="col-lg-7 offset-lg-5">
                            <div class="form-group mt-2">
                                <button type="submit"
                                    class="btn btn-lg btn-secondary">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>