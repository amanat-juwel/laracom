@include('admin.sales.GetCurrencyClass')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORDER</title>
    <link rel="stylesheet" href="{{ asset('public/admin/invoice/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/invoice/css/invoice-style.css') }}" media="screen,print">
    <link rel="stylesheet" href="{{ asset('public/admin/invoice/css/linear-font.min.css') }}">    
</head>

<body onload="//window.print();">
    <!-- START INVOICE SECTION -->
    <section class="invoice-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- START INVOICE WRAPPER -->
                    <div class="invoice-wrapper">
                        <!-- START INVOICE HEADER -->
                        <div class="invoice-header">
                            <div class="row">
                                <div class="col-md-7 col-xs-7">
                                    <div class="comapany-logo">
                                        <img src="{{ asset($globalSettings->logo) }}" alt="" class="img-responsive">
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-5">
                                    <div class="company-profile">
                                        <!--<h4>{{ strToUpper($globalSettings->company_name) }}</h4>-->
                                        <ul>
                                            <li><i class="lnr lnr-map-marker"></i> {!!$globalSettings->address!!}</li>
                                            <li><i class="lnr lnr-earth"></i> 
                                                @if($globalSettings->website != '')
                                                Website: {{$globalSettings->website}}
                                                @endif</li>
                                            <li><i class="lnr lnr-phone"></i>@if(isset($globalSettings->phone)) {{$globalSettings->phone}} @endif {{$globalSettings->mobile}} </li>
                                            <li><i class="lnr lnr-envelope"></i> {{$globalSettings->email}}</li>
                                           <!--  <li><i class="lnr lnr-user"></i> Contact person : Lorem Ipsum Dolar</li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE HEADER -->
                        <!-- START INVOICE TITLE -->
                        <div class="invoice-title">
                            <div class="row">
                                <div class="col-xs-12">
                                    <span class="invoice-text">ORDER</span>
                                    <span class="invoice-no">  #{{ $order->id }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE TITLE -->
                        <!-- START INVICE CLIENT INFO -->
                        <div class="invoice-client-info">
                            <div class="row">
                                <div class="col-xs-7">
                                    <ul>
                                        <li>Invoice To :</li>
                                        <li class="client-name">{{$billing_address->fullname}}</li>
                                        <li><i class="lnr lnr-map-marker"></i>{{$billing_address->address}}, {{$delivery_address->city}}, {{$delivery_address->country}}</li>
                                        <li><i class="lnr lnr-phone"></i>{{$billing_address->mobile}}</li>
                                        <li><i class="lnr lnr-calendar-full"></i>Invoice Date: {{ date('d M, Y', strtotime($order->datetime)) }}</li>
                                        
                                    </ul>
                                </div>
                                <div class="col-xs-5">
                                    <ul>
                                        <li>Shipping :</li>
                                        <li class="client-name">{{$delivery_address->fullname}}</li>
                                        <li><i class="lnr lnr-map-marker"></i>{{$delivery_address->address}}, {{$delivery_address->city}}, {{$delivery_address->country}}</li>
                                        <li><i class="lnr lnr-phone"></i>{{$delivery_address->mobile}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- END INVICE CLIENT INFO -->
                        <!-- START INVOICE ITEM -->
                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sl#</th>
                                                <th>Item</th>
                                                <th style="text-align:center">Quantity</th>
                                                <th style="text-align:right">Price</th>
                                                <th style="text-align:right">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @php $qty = 0; $sub_total = 0; @endphp
                                                @foreach($order_details as $key => $data)
                                                <tr>
                                                    <td style="text-align:center">{{ ++$key }}</td>
                                                    <td>{{ $data->item_name }}</td>
                                                    <td style="text-align:center">{{ $data->qty }} </td>
                                                    <td style="text-align:right">{{ number_format($data->rate,2) }} </td>
                                                    <td style="text-align:right">{{ number_format($data->rate * $data->qty,2) }}</td>
                                                </tr>
                                                @php
                                                $qty += $data->qty;
                                                $sub_total += $data->rate * $data->qty; @endphp 
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE ITEM -->
                        <!-- START INVOICE SUM -->
                        <div class="invoice-sum">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h6 class="in-word">In Words : BDT. {{ getCurrency($sub_total) }} Only</h6>
                                </div>
                                <div class="col-xs-6">
                                    <table class="table sum">
                                        <tr>
                                            <td>Net Amount:</td>
                                           <td> BDT. {{number_format($sub_total,2)}}</td>
                                        </tr>
                                      
                                    </table>
                                    <!-- START INVOICE BALANCE -->
                                    <div class="invoice-balance">
                                        @if($sub_total > 0)
                                        <div class="balance-danger">
                                            <span> Balance </span>
                                            <span> BDT. {{number_format($sub_total,2)}} </span>
                                        </div>
                                        @endif
                                    </div>
                                    <!-- END INVOICE BALANCE -->
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE SUM -->
                        <!-- START INVOICE NOTE -->
                        <div class="invoice-note">
                            <div class="row">
                                
                            </div>
                        </div>
                        <!-- END INVOICE NOTE -->
                        <!-- START INVOICE FOOTER -->
                        <div class="invoice-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE FOOTER -->
                    </div>
                    <!-- END INVOICE WRAPPER -->
                </div>
            </div>
        </div>
    </section>
    <!-- END INVOICE SECTION -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="{{ asset('public/admin/invoice/js/bootstrap.min.js') }}"></script>
</body>

</html>