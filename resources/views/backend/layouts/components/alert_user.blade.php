
{{-- FORE DEMO --}}

{{-- redirect to another dashboard UI --}}
@if (teleman_config('demo') == "YES")
    <div class="pmo-lv pmo-dark active">
        <a class="pmo-close" href="http://teleman2.thecodestudio.xyz" target="_blank"><em class="ni ni-link"></em></a>
        <a class="pmo-wrap" href="http://teleman2.thecodestudio.xyz" target="_blank">
            <div class="pmo-text text-white">
                {{ translate('Let\'s check another dashboard UI. Click here to redirect to the dashboard.') }} 
                <em class="ni ni-arrow-long-right"></em>
            </div>
        </a>
    </div>
@endif
{{-- FORE DEMO::ENDS --}}

@if (check_all_the_package_has_supported_countries())
    <div class="pmo-lv pmo-dark active">
        <a class="pmo-close" href="{{ route('dashboard.packages.index') }}"><em class="ni ni-link"></em></a>
        <a class="pmo-wrap" href="{{ route('dashboard.packages.index') }}">
            <div class="pmo-text text-white">
                {{ translate('You must check that, all the packages has supported country cost value.') }} 
                <em class="ni ni-arrow-long-right"></em>
            </div>
        </a>
    </div>
@endif
