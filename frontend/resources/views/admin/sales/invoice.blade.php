@include('admin.sales.GetCurrencyClass')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INVOICE</title>
    <link rel="stylesheet" href="{{ asset('public/admin/invoice/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/invoice/css/invoice-style.css') }}" media="screen,print">
    <link rel="stylesheet" href="{{ asset('public/admin/invoice/css/linear-font.min.css') }}" >    

</head>

<body onload="window.print();">
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
                                <div class="col-xs-7">
                                    <div class="comapany-logo">
                                        <img src="{{ asset($globalSettings->logo) }}" alt="" widht="220" height="70">
                                    </div>
                                </div>
                                <div class="col-xs-5">
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
                                    <span class="invoice-text">INVOICE</span>
                                    <span class="invoice-no"> {{ str_pad($salesMasters->sales_master_id, 4, '0', STR_PAD_LEFT) }}</span>
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
                                        <li class="client-name">{{ $salesMasters->customer_name }}</li>
                                        <li><i class="lnr lnr-map-marker"></i>@if(!empty($salesMasters->address)){{ $salesMasters->address }}@endif</li>
                                        <li><i class="lnr lnr-phone"></i>@if(!empty($salesMasters->phone)){{ $salesMasters->phone }}  @endif @if(!empty($salesMasters->mobile_no)){{ $salesMasters->mobile_no }}  @endif </li>
                                        <li><i class="lnr lnr-calendar-full"></i>Invoice Date: {{ date('d M, Y', strtotime($salesMasters->sales_date)) }}</li>
                                        
                                    </ul>
                                </div>
                                <div class="col-xs-5">
                                    <ul>
                                        <li>Shipping :</li>
                                        <li class="client-name">{{ $salesMasters->customer_name }}</li>
                                        <li><i class="lnr lnr-map-marker"></i>@if(!empty($salesMasters->address)){{ $salesMasters->address }}@endif</li>
                                        <li><i class="lnr lnr-phone"></i>@if(!empty($salesMasters->phone)){{ $salesMasters->phone }}  @endif @if(!empty($salesMasters->mobile_no)){{ $salesMasters->mobile_no }}  @endif</li>
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
                                                <th>Particulars</th>
                                               <!--  <th>Item</th> -->
                                                <th style="text-align:center">Quantity</th>
                                                <th style="text-align:right">Price</th>
                                                <!-- <th>Discount</th> -->
                                               <!-- <th>Tax</th>-->
                                                <th style="text-align:right">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @php $qty = 0; $sub_total = 0; @endphp
                                                @foreach($salesDetail as $key => $salesDetails)
                                                <tr>
                                                    <td style="text-align:center">{{ ++$key }}</td>
                                                    <td>
                                                        <b>{{ $salesDetails->item_name }}</b><br>
                                                        {{ $salesDetails->item_note }}
                                                    </td>
                                                    <td style="text-align:center">{{ $salesDetails->quantity }} </td>
                                                    <td style="text-align:right">@if($salesDetails->discounted_price!=null)<del>{{ number_format($salesDetails->mrp,2) }}</del>@endif {{ number_format($salesDetails->sales_price,2) }} </td>
                                                    <td style="text-align:right">{{ number_format($salesDetails->sales_price * $salesDetails->quantity,2) }}</td>
                                                </tr>
                                                @php
                                                $qty += $salesDetails->quantity;
                                                $sub_total += $salesDetails->sales_price * $salesDetails->quantity; @endphp 
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
                                    <h6 class="in-word">In Words : BDT. {{ getCurrency($salesMasters->memo_total-$salesMasters->discount) }} Only</h6>
                                </div>
                                <div class="col-xs-6">
                                    <table class="table sum">
                                        @if($globalSettings->vat_percent>0)
                                        <tr>
                                            <td>Vat: {{$globalSettings->vat_percent}}%:</td>
                                            <td> BDT. {{ number_format($sub_total*$globalSettings->vat_percent/100,2)}}%</td>
                                        </tr>
                                        @endif
                                        @if(count($addLessDetails)>0)
                                            @foreach($addLessDetails as $data)
                                            <tr>
                                                <td>@if($data->amount>0) Add: @else Less: @endif{{$data->particular}}:</td>
                                               <td> BDT. {{number_format($data->amount,2)}}</td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>Discount:</td>
                                           <td> BDT. {{number_format($salesMasters->discount,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td>Net Amount:</td>
                                           <td> BDT. {{number_format($salesMasters->memo_total-$salesMasters->discount,2)}}</td>
                                        </tr>
                                        <tr>
                                           <td>Paid:</td>
                                           <td> BDT. {{number_format($salesMasters->advanced_amount,2)}}</td>
                                        </tr>
                                    </table>
                                    <!-- START INVOICE BALANCE -->
                                    <div class="invoice-balance">
                                        @php 
                                        $balance = $salesMasters->memo_total-$salesMasters->discount-$salesMasters->advanced_amount;
                                        @endphp
                                        @if($balance > 0)
                                        <div class="balance-danger">
                                            <span> Balance </span>
                                            <span> BDT. {{number_format($balance,2)}} </span>
                                        </div>
                                        @else
                                        <div class="balance-success">
                                            <span> PAID </span>
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
                                <div class="col-md-12">
                                    @if($balance > 0)
                                    <p> {{ $salesMasters->customer_name }}, thank you very much. We really appreciate your business.Please send payments before the due date.</p>
                                    @endif
                                    <p>@if($salesMasters->remarks!='')
                                    <strong>Notes:</strong> {{$salesMasters->remarks}}
                                    @endif</p>
                                </div>
                            </div>
                        </div>
                        <!-- END INVOICE NOTE -->
                        <!-- START INVOICE FOOTER -->
                        <div class="invoice-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="msg-thanks pull-right">Thanks</h2>
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