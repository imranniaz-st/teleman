@extends('backend.layouts.master')

@section('title')
{{ translate('Edit') }} ⇢ {{ $contact->name }}
@endsection


@section('css')

@endsection

@section('content')

<div class="nk-block nk-block-lg">
    <div class="card card-preview">
        <div class="card-inner">
            <ul class="preview-list ">
                <li class="preview-item">
                     <a href="{{ route('dashboard.contact.index') }}" class="btn btn-md btn-secondary">
                            <em class="icon ni ni-book mr-2"></em> 
                                {{ translate('All Contacts') }}
                        </a>
                </li>
            </ul>
        </div>
    </div><!-- .card-preview -->

    <div class="card card-preview">
        <div class="card-inner">
            <form action="{{ route('dashboard.contact.update', [$contact->id, Str::slug($contact->name)]) }}" class="form-validate is-alter"
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
                                        value="{{ $contact->phone }}" placeholder="Phone Number"
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
                                @foreach(getCountry() as $country)
                                    <option value="{{ Str::lower($country) }}" {{ Str::lower($contact->country) == Str::lower($country) ? 'selected' : null }}>{{ Str::upper($country) }}
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
                                <option value="male" {{ $contact->gender == 'male' ? 'selected' : null }}>{{ Str::upper('Male') }}</option>
                                <option value="female" {{ $contact->gender == 'female' ? 'selected' : null }}>{{ Str::upper('Female') }}</option>
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
                                @foreach(professionList() as $profession)
                                    <option value="{{ Str::lower($profession) }}" {{ Str::lower($contact->profession) == Str::lower($profession) ? 'selected' : null }}>{{ Str::upper($profession) }}
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
                            <input type="text" class="form-control date-picker" name="dob" id="dob" value="{{ $contact->dob }}"
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
                                    class="btn btn-lg btn-secondary">{{ translate('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
        </div>
    </div><!-- .card-preview -->

</div>
<!-- END: Large Slide Over Toggle -->

@endsection

@section('js')

@endsection
