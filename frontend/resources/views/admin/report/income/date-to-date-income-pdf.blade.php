<!DOCTYPE html>
<html lang="en">
<head>
  <title>Income Report</title>
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
<style type="text/css">
    th,td{
        padding: 3px;
    }
</style>
<body onload="window.print();">

<!-- Main content -->
<section class="container">
    <div class="" style="margin-right: 3%; margin-left: 3%;margin-top: 3%">
        <div class="">
            <div class="row">
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    <p>Other Income Report</p>
                    <p><b>From: </b>{{date('d-m-Y', strtotime($start_date))}}, <b>To: </b>{{date('d-m-Y', strtotime($end_date))}}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%" id="purchase_details">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <th style="text-align:left">Date</th>
                                    <th style="text-align:left">Voucher Ref</th>
                                    <th style="text-align:left">Head</th>
                                    <th style="text-align:left">Particulars</th>
                                    <th style="text-align:left">Amount</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                
                                @foreach($date_to_date_incomes as $key => $data)
                                <tr>
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ $data->date }}</td>
                                    <td>{{ $data->voucher_ref }}</td>
                                    <td>{{ $data->income_head }}</td>
                                    <td>{{ $data->description }}</td>
                                    <td>{{ $data->amount }}</td>
                                   
                                    
                                </tr>
                                @php $total += $data->amount; @endphp
                                @endforeach
                                <tr>
                                    <th colspan="5">TOTAL</th>
                                    <th style="text-align:right">{{ number_format($total,2) }}</th>

                                </tr> 
                                
                            </tbody>
                        </table>    
                        <!-- <p class="text text-danger pull-right">Total: {{ number_format($total,2) }}</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- End Main Content -->
</body>
</html>