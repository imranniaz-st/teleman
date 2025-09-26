@extends('frontend.register.layouts.master')

@section('title')
    {{ appName() }} {{ translate('New Subscription') }}
@endsection

@section('css')
    
@endsection

@section('content')
	
	<div class="container-fluid">

		<div class="text-center mt-2 mb-2">
			<a href="{{ route('frontend') }}">
				<img src="{{ logo() }}" class="img-fluid m-auto" width="250" alt="{{ appName() }}">
			</a>
		</div>

	    <div class="row">
	        <!-- /content-left -->
	        <div class="col-xl-12 col-lg-12" id="start">
	            <div id="wizard_container" class="m-auto mt-2">
	                <div id="top-wizard">
	                    <span id="location"></span>
	                    <div id="progressbar"></div>
	                </div>

					@if ($errors->any())
						@foreach ($errors->all() as $error)
							<div class="alert alert-warning alert-dismissible fade show" role="alert">
								{{ $error }}

								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						@endforeach
					@endif

	                <!-- /top-wizard -->
	                <form action="{{ route('register.new.subscriber.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
	                    <input id="website" type="hidden" value="">
	                    <input id="package_id" name="package_id" type="hidden" value="{{ $package->id }}">
	                    <!-- Leave for security protection, read docs for details -->
	                    <div id="middle-wizard">
                           
                            {{-- personal information --}}
                            <div class="step">
                                <h2 class="section_title">{{ translate('Personal info') }}</h2>
	                            <div class="form-group add_top_30">
	                                <label for="name">{{ translate('Full Name') }}</label>
	                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control required" onchange="getVals(this, 'name_field');">
	                            </div>
	                            <div class="form-group">
	                                <label for="email">{{ translate('Email Address') }}</label>
	                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control required" onchange="getVals(this, 'email_field');">
	                            </div>
	                            <div class="form-group">
	                                <label for="phone">{{ translate('Phone') }}</label>
	                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control required">
                                    <small>{{ translate('Ex: +8801533149024') }}</small>
	                            </div>
	                            <div class="form-group">
	                                <label for="password">{{ translate('Password') }}</label>
	                                <input type="password" name="password" id="password" class="form-control required">
                                    <small>{{ translate('Password should be 8 characters') }}</small>
	                            </div>
	                        </div>
	                        <!-- /step-->

	                        <!-- /Restaurant == -->
	                        <div class="step">
	                            <h2 class="section_title">{{ translate('Company Information') }}</h2>
	                            <div class="form-group">
	                                <div class="form-group add_top_30">
                                        <label for="rest_name">{{ translate('Company Name') }}</label>
                                        <input type="text" name="rest_name" id="rest_name" value="{{ old('rest_name') }}" class="form-control required">
                                    </div>
                                    <div class="form-group">
                                        <label for="rest_address">{{ translate('Company Address') }}</label>
                                        <input type="text" name="rest_address" id="rest_address" value="{{ old('rest_address') }}" class="form-control required">
                                    </div>
	                            </div>
	                        </div>


							@if (teleman_config('multitenancy'))
								
                            {{-- SUBDOMAIN --}}
	                        <div class="step">
	                            <h2 class="section_title">{{ translate('Choose Subdomain') }}</h2>
                                <div class="form-group add_top_30">
                                    <div class="input-group mb-3">
                                    <input type="text" class="form-control required" id="subdomain" onkeyup="checkSubdomain()" name="domain" placeholder="Enter Subdomain">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">.{{ env('YOUR_DOMAIN') }}</span>
                                    </div>
                               
                                    <div class="invalid-feedback">
                                        {{ translate('Subdomain is already taken.') }}
                                    </div>

                                    <div class="valid-feedback">
                                        {{ translate('Subdomain is available.') }}
                                    </div>

                                    <div class="invalid-subdomain d-none">
                                        {{ translate('Invalid subdomain.') }}
                                    </div>
                                    

                                    </div>
                                </div>
	                        </div>
                            {{-- SUBDOMAIN::END --}}
							@endif


	                        <div class="submit step" id="end">
	                            <div class="summary">
	                                <div class="wrapper">
	                                    <h3>{{ translate('Thank your for your time') }}<br><span id="name_field"></span>!</h3>
	                                    <p>{{ translate('We will contat you shorly at the following email address') }} <strong id="email_field"></strong></p>
	                                </div>

									@if (application('google_recaptcha_mode') == 'YES') 

									<div class="form-group form-control-wrap">
                                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                        <div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
										@error('g-recaptcha-response')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
									</div>
										
									@endif

									

	                                <div class="text-center">
	                                    <div class="form-group terms">
	                                        <label class="container_check">{{ translate('Please accept our') }} {{ translate('Terms and conditions') }} {{ translate('before Submit') }}
	                                            <input type="checkbox" name="terms" value="Yes" class="required">
	                                            <span class="checkmark"></span>
	                                        </label>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <!-- /step last-->

	                    </div>
	                    <!-- /middle-wizard -->
	                    <div id="bottom-wizard">
	                        <button type="button" name="backward" class="backward">{{ translate('Prev') }}</button>
	                        <button type="button" name="forward" class="forward">{{ translate('Next') }}</button>
	                        <button type="submit" class="submit">{{ translate('Submit') }}</button>
	                    </div>
	                    <!-- /bottom-wizard -->
	                </form>
	            </div>
	            <!-- /Wizard container -->
	        </div>
	        <!-- /content-right-->
	    </div>
	    <!-- /row-->
	</div>
	<!-- /container-fluid -->

<input type="hidden" value="{{ route('check.domain') }}" id="check_domain_url">
<input type="hidden" value="{{ env('YOUR_DOMAIN') }}" id="base_url">

@endsection
    
@section('js')
    <script src="{{ asset('backend/js/main.js') }}"></script>
@endsection

