<!DOCTYPE html>
<html lang="en">
<head>
  <title>Income Statement Report</title>
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
    <div class="">
        <div class="">
            <div class="row">
                <center>
                    <p>Income Statement Report for the period</p>
                    <p><b>From: </b>{{$start_date}}, <b>To: </b>{{$end_date}}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <h4><strong>Revenue</strong></h4>
                        <table class="table-bordered" style="width: 50%">

                            <tr>
                                <td style="width: 70%">Sales</td>
                                <td style="text-align: right;">{{number_format($income,2)}}</td>
                            </tr>
                            <tr>
                                <td>Other</td>
                                <td style="text-align: right;">00</td>
                            </tr>
                            <tr>
                                <td><strong>Total Revenue</strong></td>
                                <td style="text-align: right;"><strong>{{number_format($income,2)}}</strong></td>
                            </tr>
                        </table>
                        <br>
                        <h4><strong>Expense</strong></h4>
                        <table class="table-bordered" style="width: 50%">

                            <tr>
                                <td style="width: 70%">Cost of Goods Sold</td>
                                <td style="text-align: right;">{{number_format($cost_of_goods,2)}}</td>
                            </tr>
                            <tr>
                                <td>Operating Expense</td>
                                <td style="text-align: right;">{{number_format($expense,2)}}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Expense</strong></td>
                                <td style="text-align: right;"><strong>{{number_format($expense+$cost_of_goods,2)}}</strong></td>
                            </tr>
                        </table>
                        <br>
                        <h4><strong>Net Income</strong></h4>
                        <table class="table-bordered" style="width: 50%">
                        <tr>
                            <td style="width: 70%">Income before tax</td>
                            <!-- <td style="text-align: center;"><strong>{{number_format($income,2)}} - {{number_format($expense+$cost_of_goods,2)}}</strong></td> -->
                            <td style="text-align: right;">{{number_format($income-$expense-$cost_of_goods,2)}}</td>
                        </tr>
                        <tr>
                            <td style="width: 70%">Tax Expense</td>
                            <td style="text-align: right;">
                                @if($income-$expense-$cost_of_goods <= 250000)
                                0.00
                                @elseif($income-$expense-$cost_of_goods > 250000 && $income-$expense-$cost_of_goods <= 650000)
                                    @php
                                        $tax=($income-$expense-$cost_of_goods) + ((10/100) * ($income-$expense-$cost_of_goods))
                                    @endphp
                                {{number_format($tax,2)}}
                                @endif
                            </td>
                        </tr> 
                        <tr>
                            <td style="width: 70%"><strong>Net profit</strong></td>
                            <td style="text-align: right;"><strong>{{number_format($income-$expense-$cost_of_goods,2)}}</strong></td>
                        </tr> 
                    </table>    
                        <br>
                        <p class="pull-right">All amounts in Taka*</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <p style="font-size: 90%; text-align: center">       
         © Copright 2018 | All Rights Reserved To {{ $globalSettings->company_name }} | Powered By C133008-C133024
    </p>   -->  
</section>
<!-- End Main Content -->
</body>
</html>