<div class="section is-pricing is-medium">
    <div class="container">
        <div class="section-title-wrapper">
            <div class="bg-number">4</div>
            <h2 class="title section-title has-text-centered dark-text text-bold editable is-modified"
                 data-cid="70" tabindex="1">
                {{ saasContent(70) ?? 'Pricing' }}
            </h2>
        </div>
        <div class="content-wrapper">
            <div class="fancy-pricing">
                <div class="columns is-vcentered">

                    @forelse(activePackages() as $package)

                    <div class="column is-3">
                        <!-- Pricing table -->
                        <div class="
                            flex-card
                            fancy-pricing-card
                            light-bordered
                            hover-inset
                            secondary
                        ">
                            <h3 class="plan-name">{{ $package->name }}</h3>
                            <div class="plan-icon">
                                <i class="im im-icon-Mustache-{{ $loop->iteration + 1 }}"></i>
                            </div>
                            <div class="plan-price">
                                {{ price($package->price) }} <small><b>/{{ $package->range }} {{ Str::plural($package->range_type, $package->range) }}</b></small>
                            </div>
                            
                            <ul class="plan-features">
                                <li>
                                    <a class="feature-count-text see-more-btn raised modal-trigger"
                                        data-modal="basic-modal{{ $loop->iteration }}">
                                        {{ translate('Supported Countries') }}
                                    </a>
                                </li>
                                <li><span class="feature-count-text">{{ $package->credit }} {{ Str::plural('credit', $package->credit) }}</span></li>
                                <li><span class="feature-count-text">{{ $package->call_cost_per_second }} {{ Str::plural('credit', $package->call_cost_per_second) }} {{ translate('per second') }}</span></li>
                                
                                @forelse (json_decode($package->feature_id, true) as $feature)
                                    @if (featureName($feature))
                                        <li><span class="feature-count-text">{{ featureName($feature) }}</span></li>
                                    @endif
                                @empty
                                    
                                @endforelse
                            </ul>
                            <div class="pt-20 pb-20">
                                @auth
                                @if (checkUserTrialUsed(Auth::user()->id) != 'true')
                                  <form action="{{ route('renew.subscriber.store') }}" method="POST">
                                      @csrf
                                      <input type="hidden"
                                            name="package_id"
                                            value="{{ $package->id }}">
                                      <button type="submit"
                                              class="button button-cta
                                                btn-align
                                                rounded
                                                raised
                                                secondary-btn
                                                btn-outlined
                                                is-bold">
                                              {{ translate('Get Started') }}
                                      </button>
                                  </form>
                                @elseif($package->trial != 1)
                                    <form action="{{ route('renew.subscriber.store') }}" method="POST">
                                      @csrf
                                      <input type="hidden"
                                            name="package_id"
                                            value="{{ $package->id }}">
                                      <button type="submit"
                                              class="button button-cta
                                                btn-align
                                                rounded
                                                raised
                                                secondary-btn
                                                btn-outlined
                                                is-bold">
                                              {{ translate('Get Started') }}
                                      </button>
                                  </form>
                                @else
                                <a href="javascript:;"
                                  data-toggle="tooltip"
                                  data-placement="top"
                                  class="disabled button button-cta
                                                btn-align
                                                rounded
                                                raised
                                                secondary-btn
                                                btn-outlined
                                                is-bold"
                                  title="You completed the trial period">
                                      {{ translate('Not Applicable') }}
                                </a>
                                @endif
                              @endauth

                              @guest
                                <a href="{{ route('register.new.subscriber', $package->slug) }}" 
                                    class="button button-cta
                                    btn-align
                                    rounded
                                    raised
                                    secondary-btn
                                    btn-outlined
                                    is-bold">
                                    {{ translate('Get Started') }}
                                </a>
                              @endguest

                            </div>
                        </div>
                    </div>

                    <!-- Modal Markup -->
                        <div id="basic-modal{{ $loop->iteration }}" class="modal">
                            <div class="modal-background"></div>
                            <div class="modal-content">
                                

                                <table class="table compare-table">
                        <thead>
                            <tr>
                                <th>{{ translate('COUNTRY') }}</th>
                                <th>{{ translate('CODE') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($package->supported_countries as $country)
                                <tr>
                                    <td class="text-align-center">{{ Str::upper($country->twilio_call_cost->country) }}</td>
                                    <td>{{ $country->twilio_call_cost->code }}</td>
                                </tr>
                            @empty
                                
                            @endforelse
                            
                        </tbody>
                    </table>

                            </div>
                            <button class="modal-close is-large is-hidden" aria-label="close"></button>
                        </div>
                    <!-- /Modal Markup -->

                    @empty

                    @endforelse

                </div>

            </div>
        </div>
    </div>
</div>
