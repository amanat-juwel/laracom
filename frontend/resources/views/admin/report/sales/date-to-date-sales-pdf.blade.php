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
                <img src='{{ asset("$globalSettings->logo") }}' height="75" width="220">
                    <p>Sales Report</p>
                    <p><b>From: </b>{{ $start_date}} <b>To: </b>{{ $end_date}}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%" id="purchase_details">
                            <thead>
                                <tr>
                                    <th height="25">Sl No.</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Invoice No</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($date_to_date_sales))
                                    @foreach($date_to_date_sales as $key => $data)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $data->item_name }}</td>
                                            <td>{{ $data->quantity }}</td>
                                            <td>{{ $data->sales_price }}</td>
                                            <td>{{$globalSettings->invoice_prefix."-BI-".str_pad($data->sales_master_id, 8, '0', STR_PAD_LEFT)}}</td>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p style="font-size: 90%; text-align: center">       
          © Copright {{date('Y')}} | All Rights Reserved To {{ $globalSettings->company_name }} | Powered By V-Link Network
    </p>
</section>
<!-- End Main Content -->
</body>
</html>