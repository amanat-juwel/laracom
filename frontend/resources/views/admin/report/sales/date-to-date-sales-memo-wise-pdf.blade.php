<!DOCTYPE html>
<html lang="en">
<head>
  <title> Sales Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
<body onload="window.print();">

<!-- Main content -->
<section class="content">
    <div style="margin: 10%">
        <div>
            <div class="row">
                <center>
                <!-- <img src='{{ asset("$globalSettings->logo") }}' height="75" width="220"> -->
                    <h4>{{$globalSettings->company_name}}</h4>
                    Sales Report</br>
                    <b>From: </b>{{ $start_date}} <b>To: </b>{{ $end_date}}</br><br>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%" id="purchase_details">
                            <thead>
                                <tr>
                                    <th height="25">Sl No.</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th style="text-align: center">Showing Price</th>
                                    <th style="text-align: center">Discount</th>
                                    <th style="text-align: center">Deposit</th>
                                    <th style="text-align: center">Due</th>
                                </tr>

                            </thead>

                            <tbody>
                                @php $net_sales=0; $net_discount=0; $net_paid=0; $net_due=0;
                                @endphp
                                @foreach($date_to_date_sales as $key => $data)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{$globalSettings->invoice_prefix."-BI-".str_pad($data->sales_master_id, 8, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{ $data->customer_name }} {{ $data->mobile_no }}</td>
                                        <td style="text-align: right">{{ number_format($data->memo_total,2) }}</td>
                                        <td style="text-align: right">{{ number_format($data->discount,2) }}</td>
                                        <td style="text-align: right">{{ number_format($data->advanced_amount,2) }}</td>
                                        <td style="text-align: right">{{ number_format($data->memo_total-$data->discount-$data->advanced_amount,2) }}</td>

                                    </tr>
                                    @php 
                                        $net_sales += $data->memo_total;
                                        $net_discount += $data->discount;
                                        $net_paid += $data->advanced_amount;
                                        $net_due += ($data->memo_total-$data->discount-$data->advanced_amount);
                                    @endphp
                                @endforeach
                                <tr>
                                    <th height="25" colspan="3" style="text-align: center">TOTAL</th>
                                    <th style="text-align: right">{{ number_format($net_sales,2) }}</th>
                                    <th style="text-align: right">{{ number_format($net_discount,2) }}</th>
                                    <th style="text-align: right">{{ number_format($net_paid,2) }}</th>
                                    <th style="text-align: right">{{ number_format($net_due,2) }}</th>

                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!--   <p style="font-size: 90%; text-align: center">       
          © Copright {{date('Y')}} | All Rights Reserved To {{ $globalSettings->company_name }} | Powered By V-Link Network
    </p> -->
</section>
<!-- End Main Content -->
</body>
</html>