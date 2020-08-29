<!DOCTYPE html>
<html lang="en">
<head>
  <title>  Report</title>
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
<body onload="//window.print();">

<!-- Main content -->
<section class="content">
    <div style="margin: 4%">
        <div>
            <div class="row">
                <center>
                <!-- <img src='{{ asset("$globalSettings->logo") }}' height="75" width="220"> -->
                    <h4>{{$globalSettings->company_name}}</h4>
                    Grand Payment Receivable</br>
                    @php date_default_timezone_set("Asia/Dhaka"); @endphp
                    As of Date: {{ date('F j, Y') }} {{ date('h:i:s a') }}</br></br>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" id="" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Cust ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <!-- <th>Email</th> -->
                                    <!-- <th>Category</th> -->
                                    <th>Balance</th>

                                </tr>
                            </thead>
                            <tbody id="myTable">
                                @if(isset($grandReceivable))
                                    @php $final_balance = 0; @endphp
                                    @php $total = 0; @endphp
                                    @foreach($grandReceivable as $key => $data)
                                    @php
                                        $balance = $data->credit + $data->op_bal_credit - ($data->debit + $data->op_bal_debit);
                                        $total += $balance;
                                    @endphp
                                    @if($balance != 0)
                                    @php
                                        $total += $balance;
                                    @endphp
                                    <tr>
                                        <td height="25">{{ "SFL-".str_pad($data->customer_code, 8, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $data->customer_name }}</td>
                                        <td>{{ $data->mobile_no }}</td>
                                        <td>{{ $data->address }}</td>
                                        <!-- <td>{{ $data->email }}</td> -->
                                        <!-- <td>{{ $data->category }}</td> -->
                                        
                                        @if($balance == 0)
                                        <td class="" style="text-align: right;"> 0.00 </td>
                                        @elseif($balance < 0)
                                        <td class="" style="text-align: right;">{{ number_format(abs($balance),2) }}</td>
                                        @else($balance > 0)
                                        <td class="" style="text-align: right;">{{ number_format($balance,2) }}</td>
                                        @endif
                                    
                                    </tr>
                                    @php $final_balance += $balance; @endphp
                                    @endif
                                    @endforeach
                                    <tr>
                                        <th colspan="4" class="text-center">TOTAL</th>
                                        <th  style="text-align: right;">{{ number_format(abs($final_balance),2) }}</th>
                                    </tr>
                                @endif
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
