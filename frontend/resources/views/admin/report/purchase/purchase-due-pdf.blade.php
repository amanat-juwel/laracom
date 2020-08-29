<!DOCTYPE html>
<html lang="en">
<head>
  <title>Todays Purchase Report</title>
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
<section class="content" style="margin-right: 10%; margin-left: 10%;margin-top: 10%">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <center>
                    <img src='{{ asset("$globalSettings->logo") }}' height="75" width="220">
                    <p>All Purchase Due Report</p>
                    <p><b>Print Date: </b><?php echo date("F d, Y");?></p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Supplier Name</th>
                                    <th>Purchase Date</th>
                                    <th>Invoice No.</th>
                                    <th>Invoice Total</th>
                                    <th>Paid</th>
                                    <th>Discount</th>
                                    <th>Due</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($purchase_details))
                                    @php $total = 0; @endphp
                                    @foreach($purchase_details as $key => $purchase_detail)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $purchase_detail->sup_name }}</td>
                                            <td>{{ $purchase_detail->purchase_date }}</td>
                                            <td>{{ $purchase_detail->memo_no }}</td>
                                            
                                            <td style="text-align: right;">{{ number_format($purchase_detail->memo_total,2) }}</td>
                                            <td style="text-align: right;">{{ number_format($purchase_detail->advanced_amount,2) }}</td>
                                            <td style="text-align: right;">{{ number_format($purchase_detail->discount,2) }}</td>
                                            <td style="text-align: right;">{{ number_format($purchase_detail->memo_total - $purchase_detail->advanced_amount -$purchase_detail->discount,2) }}</td>   
                                        </tr>
                                    @php
                                        $total += $purchase_detail->memo_total - $purchase_detail->advanced_amount -$purchase_detail->discount;    
                                    @endphp 
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <p class="text text-danger pull-right">Total: {{ number_format($total,2) }}</p>
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