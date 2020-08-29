<!DOCTYPE html>
<html lang="en">
<head>
  <title> Report</title>
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
                    <p>Expense Report</p>
                    <p><b>From: </b>{{date('d-m-Y', strtotime($start_date))}}, <b>To: </b>{{date('d-m-Y', strtotime($end_date))}}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" id="" width="100%">
                            <thead>
                                <tr>
                                    <th style="width: 80%;">Account</th>
                                    <th style="width: 20%;">Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php $total_balance = 0; @endphp
                                @foreach($date_to_date_expenses as $data)
                                <tr>
                                    <td>{{ $data->bank_name }}</td>
                                    <td class="amount">{{ number_format($data->balance,2) }}</td>
                                </tr>
                                @php $total_balance += $data->balance; @endphp
                                @endforeach

                                <tr>
                                    <td class="text-bold">TOTAL</td>
                                    <td class="amount text-bold">{{ number_format($total_balance,2) }}</td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- End Main Content -->
</body>
</html>