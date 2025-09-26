        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Total Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Total Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['total_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Completed Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Completed Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['completed_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Queued Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Queued Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['queue_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('No Answered Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('No Answered Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['no_answer_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Initiated Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Initiated Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['initiated_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .card -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Ringing Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Ringing Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['ringing_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Busy Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Busy Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['busy_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Canceled Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Canceled Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['canceled_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-bordered">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-2">
                        <div class="card-title">
                            <h6 class="title">{{ translate('Failed Calls') }}</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="{{ translate('Failed Calls') }}"></em>
                        </div>
                    </div>
                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                        <div class="nk-sale-data">
                            <span class="amount">{{ twilio_analytics($account_sid, $phone)['failed_calls'] }}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
<script src="{{ asset('backend/js/loader.js') }}"></script>