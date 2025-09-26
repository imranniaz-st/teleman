@extends('backend.layouts.master')

@section('title')
    {{ translate('Limit Manager') }} - {{ $client->name }}
@endsection

@section('css')
    
@endsection
    
@section('content')

<div class="card card-bordered">
    <div class="card-inner">
        <div class="card-head">
            <h5 class="card-title">{{ translate('Limit Manager') }}</h5>
        </div>
        <form action="{{ route('dashboard.clients.limit.manager.update', [$client->id, Str::slug($client->name)]) }}" class="gy-3 form-validate is-alter" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-name">{{ translate('Credits') }}</label>
                        <span class="form-note">{{ translate('Specify the credits') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="site-name" name="credit" value="{{ $client->item_limit_count->credit }}" required="">
                            <small>{{ translate('Price is in US dollars') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Plan Period') }}</label>
                        <span class="form-note">{{ translate('Specify the plan period') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Start to End date') }}</label>
                        <div class="form-control-wrap">
                            <div class="input-daterange date-picker-range input-group">
                                <input type="text" class="form-control" name="start_at" value="{{ Carbon\Carbon::parse($client->subscription->start_at)->format('m-d-Y') }}" required=""/>
                                <div class="input-group-addon">{{ translate('TO') }}</div>
                                <input type="text" class="form-control" name="end_at" value="{{ Carbon\Carbon::parse($client->subscription->end_at)->format('m-d-Y') }}" required=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-center">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="form-label" for="site-off">{{ translate('Add Payment') }}</label>
                        <span class="form-note">{{ translate('Add to the payment history') }}.</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="add_payment" value="0" id="payment-off" onchange="PaymentFormCheckBox(this.value)">
                            <label class="custom-control-label" for="payment-off"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="PaymentForm d-none">
                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="price">{{ translate('Price') }} ({{ curr() }})</label>
                            <span class="form-note">{{ translate('Enter payment amount') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="price" required="" name="amount" placeholder="{{ curr() }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label" for="trx_id">{{ translate('Transaction ID') }}</label>
                            <span class="form-note">{{ translate('Enter Transaction ID') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="trx_id" required="" name="trx_id" placeholder="Enter Transaction ID">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-center">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <label class="form-label">{{ translate('Payment Method') }}</label>
                            <span class="form-note">{{ translate('Specify the payment method') }}.</span>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <select class="form-select" single="single" data-placeholder="Select Payment Method" name="payment_gateway">
                                    @foreach (availableGateways() as $availableGateway)
                                        <option value="{{ $availableGateway['slug'] }}">{{ $availableGateway['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="row g-3">
                <div class="col-lg-7 offset-lg-5">
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-lg btn-secondary">{{ translate('Update') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div><!-- card -->
            
@endsection

@section('js')
    
@endsection