
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ appName() }}</title>

        <!-- favicon -->
        <link
            rel="icon"
            href="{{ logo() }}"
            sizes="16x16"
            type="image/png"
        />

        <!-- Stylesheet Link -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/invoice/style.css') }}" media="all"/>
     
<style>

        </style>

    </head>
    <body class="t-bg-white">
        <div class="fk-print t-pt-30 t-pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                       
                        <span class="d-block fk-print-text fk-print-text--bold text-uppercase fk-print__title text-center">
                            {{ orgName() }}
                        </span>

                  
                        <p class="mb-0 xsm-text fk-print-text text-center text-capitalize">
                            {{ orgAddress() }}
                        </p>

                        <p class="mb-0 xsm-text fk-print-text text-center text-capitalize">
                            call: {{ orgPhone() }}
                        </p>

                        <hr>

                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            invoice no: #{{ $details->invoice }}
                        </p>

                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            date: {{ Carbon\Carbon::now()->format('d-m-Y') }}
                        </p>

                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            client name: {{ getUserInfo($details->user_id)->name ?? null }}
                        </p>

                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            Cclient email: {{ getUserInfo($details->user_id)->email ?? null }}
                        </p>

                        <table class="table mb-0 table-borderless">
                            <thead>
                                <tr>
                                  <th scope="col" class="fk-print-text fk-print-text--bold sm-text text-capitalize">plan</th>
                                  <th scope="col" class="fk-print-text fk-print-text--bold sm-text text-capitalize text-right">price</th>
                                </tr>
                            </thead>
                            <tbody>
                             
                                <tr>
                                  <th class="fk-print-text xsm-text text-capitalize">
                                      <span class="d-block">
                                          Free plan
                                      </span>
                                  </th>
                                  <td class="fk-print-text xsm-text text-capitalize text-right">{{ $details->amount }}</td>
                                </tr>
                            
                            </tbody>
                        </table>



                        <hr class="m-0">
                        <table class="table mb-0 table-borderless">
                            <tbody>
                                <tr>
                                  <th class="fk-print-text xsm-text text-capitalize">
                                      <span class="d-block">
                                          total
                                      </span>
                                  </th>
                                  <td class="fk-print-text xsm-text text-capitalize text-right">{{ price($details->amount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                   
                      
                  
                        <hr class="mt-0">
                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            prepared by: {{ orgName() }}
                        </p>
                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            payment method: {{ Str::upper($details->payment_gateway) }}
                        </p>
                        <p class="mb-0 xsm-text fk-print-text text-capitalize">
                            status: {{ $details->payment_status }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>


    


</html>


