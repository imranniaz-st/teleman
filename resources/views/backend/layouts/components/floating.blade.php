
<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="javascript:;" class="floatingButton">
            ⇱
        </a>
        <ul class="floatingMenu">
            
            @can('admin')
            <li>
                <a href="{{ route('dashboard.application.setup') }}">{{ translate('Application Setup') }}</a>
            </li>
            <li>
                <a href="{{ route('language.index') }}">{{ translate('Language') }}</a>
            </li>
            @endcan
            <li>
                <a href="{{ route('dashboard.contact.index') }}">{{ translate('New Contact') }}</a>
            </li>
            <li>
                <a href="{{ route('dashboard.campaign.index') }}">{{ translate('Campaigns') }}</a>
            </li>
            <li>
                <a href="{{ route('dialer.index') }}">{{ translate('Web Dialer') }}</a>
            </li>
            @can('customer')
            <li>
                <a href="{{ route('frontend.pricing') }}">{{ translate('Top Up') }}</a>
            </li>
            @endcan
            <li>
                <a href="{{ route('dashboard.contact.group.index') }}">{{ translate('Groups') }}</a>
            </li>
            
            <li>
                <a href="{{ route('dashboard.campaign.leads') }}">{{ translate('Leads') }}</a>
            </li>
           
        </ul>
    </div>
</div>