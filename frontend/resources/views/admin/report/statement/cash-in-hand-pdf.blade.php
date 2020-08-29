
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Todays Cash in Hand Report</title>
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

            <div class="row"  style="margin-top: 10%;margin-left: 6%;margin-right: 6%">
                <center>
                    <!-- <img src='{{ asset("$globalSettings->logo") }}' height="75" width="220"> -->
                    <h4>{{$globalSettings->company_name}}</h4>
                    <p>Transaction Statement</p>
                    <p><b>Date: </b>{{ $reporting_date }}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <!-- START TODAYS SUMMARY-->
                        <h4><u>Today's Summary</u></h4>
                        <table class="table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center">Date</th>
                                    <th style="text-align:center">Sales</th>
                                    <th style="text-align:center">Sales Due</th>
                                    <th style="text-align:center">Due Received</th>
                                    <th style="text-align:center">Purchase</th>
                                    <th style="text-align:center">Purchase Due</th>
                                    <th style="text-align:center">Due Paid</th>
                                    <th style="text-align:center">Expense</th>
                                    <th style="text-align:center">Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($reporting_date))
                                
                                <tr>
                                    <td>{{ $reporting_date }}</td>
                                    <td style="text-align:right">@foreach($sale_today as $sale) {{ number_format($sale_tdy = $sale->credit,2) }}  @endforeach</td>
                                    <td style="text-align:right">@foreach($customer_due_today as $customer_due) {{ number_format($customer_due->debit-$customer_due->credit,2) }}  @endforeach</td>
                                    <td style="text-align:right">{{ number_format($due_received_today,2) }}</td>
                                    <td style="text-align:right">@foreach($purchase_today as $purchase) {{ number_format($purchase_tdy =  $purchase->debit,2) }}  @endforeach</td>
                                    <td style="text-align:right">@foreach($supplier_due_today as $supplier_due) {{ number_format($supplier_due->credit-$supplier_due->debit,2) }}  @endforeach</td>
                                    <td style="text-align:right">{{ number_format($due_paid_today,2) }}</td>
                                    <td style="text-align:right">@foreach($expense_today as $expense) {{ number_format($expense_tdy = $expense->amount,2) }}  @endforeach</td>
                                    @php $balance = $due_received_today + $sale_tdy - $purchase_tdy - $due_paid_today - $expense_tdy @endphp
                                    @if($balance>0)
                                    <td style="text-align:right">{{ number_format($balance, 2) }} Dr</td>
                                    @else
                                    <td style="text-align:right">{{ number_format(abs($balance), 2) }} Cr</td>
                                    @endif
                                </tr>
                               
                                @endif
                            </tbody>
                        </table>
                        <!-- END TODAYS SUMMARY-->
                        <!-- START TRANSACTION DETAILS-->
                        <h4><u>Transaction Details</u></h4>
                        <table class="table-bordered" width="100%">
                            <thead>
                                <tr>
                                        <th>Srl.</th>
                                        <th>Date</th>
                                        <th style="text-align:center">Account</th>
                                        <th style="text-align:center">Description</th>
                                        <th style="text-align:center">Debit</th>
                                        <th style="text-align:center">Credit</th>
                                    </tr>
                            </thead>

                            <tbody>
                                    @if(isset($transactions))
                                        @foreach($transactions as $key => $transaction)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $transaction->transaction_date }}</td>
                                            <td>{{ $transaction->bank_name }}</td>
                                            <td>{{ $transaction->transaction_description }}</td>
                                            <td style="text-align:right">@php echo number_format($transaction->deposit,2) @endphp</td>
                                            <td style="text-align:right">@php echo number_format($transaction->expense,2) @endphp</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                        </table>
                        <!-- END TRANSACTION DETAILS-->
                        <!-- START ACCOUNT SUMMARY-->
                        <h4><u>Account Summary</u></h4>
                        <table  class="table-striped" border="1" width="100%">
                            <tr style="font-weight: bold;text-align: center;">
                                <td rowspan="2">Srl</td>
                                <td rowspan="2">Account</td>
                                <td colspan="2">Opening Balance</td>
                                <td colspan="2">Transaction Balance</td>
                                <td colspan="2">Closing Balance</td>
                            </tr>
                            <tr style="font-weight: bold;text-align: center;">
                                <td>Debit</td>
                                <td>Credit</td>
                                <td>Debit</td>
                                <td>Credit</td>
                                <td>Debit</td>
                                <td>Credit</td>
                            </tr>
                            <tr>
                                <td colspan="8"><strong>Accounts</strong></td>
                            </tr>
                            @php $srl = 1; $sum_op_dr = 0; $sum_op_cr = 0; 
                            $sum_transaction_dr = 0; $sum_transaction_cr = 0;
                            $sum_cls_dr = 0; $sum_cls_cr = 0; @endphp
                            <!-- CASH/BANK ACCOUNTS--> 
                            @foreach($view_of_accounts as $item)
                            <tr>
                                <td>{{ $srl++ }}</td>
                                <td>{{ $item['bank_name'] }}</td>
                                <td style="text-align: right">@if($item['opening_balance']>=0){{ number_format(floatVal($item['opening_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['opening_balance']<=0){{ number_format(floatVal(abs($item['opening_balance'])),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['transaction_balance']>=0){{ number_format(floatVal($item['transaction_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['transaction_balance']<=0){{ number_format(floatVal(abs($item['transaction_balance'])),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['closing_balance']>=0){{ number_format(floatVal($item['closing_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['closing_balance']<=0){{ number_format(floatVal(abs($item['closing_balance'])),2) }}@else 0.00 @endif</td>
                            </tr>
                            @php
                                if($item['closing_balance']>=0){
                                    $sum_cls_dr += $item['closing_balance'];
                                }
                                elseif($item['closing_balance']<=0){
                                     $sum_cls_cr += $item['closing_balance'];
                                }
                            @endphp
                            @endforeach
                            <!-- CASH/BANK ACCOUNTS--> 

                        </table>
                        <!-- END ACCOUNT SUMMARY-->
                    </div>
                </div>
            </div>
    <br>
    <p style="font-size: 90%; text-align: center">       
         © Copright {{date('Y')}} | All Rights Reserved To {{ $globalSettings->company_name }} | Powered By V-Link Network
    </p>
</section>
<!-- End Main Content -->
</body>
</html>