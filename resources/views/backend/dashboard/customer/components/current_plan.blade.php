<div class="nk-block">
<div class="card card-bordered">
        <div class="card-inner-group">
            <div class="card-inner">
                <div class="between-center flex-wrap flex-md-nowrap g-3">
                    <div class="nk-block-text">
                        <h6>{{ billingPlan()->package->name }} {{ translate('Plan') }} - <span
                                class="text-base">{{ price(billingPlan()->amount) }} /
                                {{ billingPlan()->package->range_type }}</span></h6>
                        <p class="text-soft">{{ translate('99.95% uptime, powerfull features and more...') }}</p>
                    </div>
                    <div class="nk-block-actions flex-shrink-0">
                        <a href="{{ route('frontend.pricing') }}" class="btn btn-secondary">{{ translate('Change Plan') }}</a>
                    </div>
                </div>
            </div><!-- .nk-card-inner -->
        </div>
    </div><!-- .card -->
</div>