<!DOCTYPE html>
<html lang="en">
<head>
  <title>Trial Balance</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style type="text/css" media="print">
    @page { 
    size: auto;
    margin: 6mm 0 10mm 0;
    }
    body {
        margin:0;
        padding:0;
    }

</style>

<body onload="window.print();">

<!-- Main content -->
<section class="content" >
    <div class="">
        <div class="">
            <div class="row" style="margin:5%">
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    <p>Trial Balance</p>
                    <p>For the Period <b>From: </b>{{$start_date}}, <b>To: </b>{{$end_date}}</p>
                </center>
                <div class="col-md-12">
                    @if(isset($start_date))
                    <div class="table-responsive">
                        
                        <!-- <table class="table table-bordered" id="purchase_details">-->                        
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
                                <td colspan="8"><strong>Cash/Bank/Other</strong></td>
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
                                <td style="text-align: right">{{ number_format(floatVal($item['transaction_balance_debit']),2) }}</td>
                                <td style="text-align: right">{{ number_format(floatVal(abs($item['transaction_balance_credit'])),2) }}</td>
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
                            <tr>
                                <td colspan="8"><strong>Customer </strong></td>
                            </tr>
                            <!-- SALES ACCOUNTS--> 
                            @foreach($view_of_sales_accounts as $item)
                            <tr>
                                <td>{{ $srl++ }}</td>
                                <td>{{ $item['customer_name'] }}</td>
                                <td style="text-align: right">@if($item['opening_balance']>=0){{ number_format(floatVal($item['opening_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['opening_balance']<=0){{ number_format(floatVal(abs($item['opening_balance'])),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">{{ number_format(floatVal($item['transaction_balance_debit']),2) }}</td>
                                <td style="text-align: right">{{ number_format(floatVal(abs($item['transaction_balance_credit'])),2) }}</td>
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
                            <!-- SALES ACCOUNTS-->

                            <!-- SALES RETURN ACCOUNTS--> 
                            <!-- <tr>
                                <td colspan="8"><strong>Sales Return</strong></td>
                            </tr>
                            @foreach($view_of_sales_return as $item)
                            <tr>
                                <td>{{ $srl++ }}</td>
                                <td>{{ $item['account_name'] }}</td>
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
                            @endforeach -->
                            <!-- SALES RETURN ACCOUNTS-->


                            <tr>
                                <td colspan="8"><strong>Supplier</strong></td>
                            </tr>
                            <!-- PURCHASE ACCOUNTS--> 
                            @foreach($view_of_purchase_accounts as $item)
                            <tr>
                                <td>{{ $srl++ }}</td>
                                <td>{{ $item['sup_name'] }}</td>
                                <td style="text-align: right">@if($item['opening_balance']>=0){{ number_format(floatVal($item['opening_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['opening_balance']<=0){{ number_format(floatVal(abs($item['opening_balance'])),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">{{ number_format(floatVal($item['transaction_balance_debit']),2) }}</td>
                                <td style="text-align: right">{{ number_format(floatVal(abs($item['transaction_balance_credit'])),2) }}</td>
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
                            <!-- PURCHASE ACCOUNTS-->
                            <tr>
                                <td colspan="8"><strong>Other Income</strong></td>
                            </tr>
                            <!-- OTHER INCOME ACCOUNTS--> 
                            @foreach($view_of_other_income as $item)
                            <tr>
                                <td>{{ $srl++ }}</td>
                                <td>{{ $item['income_head'] }}</td>
                                <td style="text-align: right">@if($item['opening_balance']<=0){{ number_format(floatVal($item['opening_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['opening_balance']>=0){{ number_format(floatVal(abs($item['opening_balance'])),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['transaction_balance']<=0){{ number_format(floatVal($item['transaction_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['transaction_balance']>=0){{ number_format(floatVal(abs($item['transaction_balance'])),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['closing_balance']<=0){{ number_format(floatVal($item['closing_balance']),2) }}@else 0.00 @endif</td>
                                <td style="text-align: right">@if($item['closing_balance']>=0){{ number_format(floatVal(abs($item['closing_balance'])),2) }}@else 0.00 @endif</td>
                            </tr>
                            @php
                                if($item['closing_balance']<=0){
                                    $sum_cls_dr += $item['closing_balance'];
                                }
                                elseif($item['closing_balance']>=0){
                                     $sum_cls_cr -= $item['closing_balance'];
                                }
                            @endphp
                            @endforeach
                            <!-- OTHER INCOME ACCOUNTS-->
                            <tr>
                                <td colspan="8"><strong>Expense</strong></td>
                            </tr>
                            <!-- EXPENSE ACCOUNTS--> 
                            @foreach($view_of_expense as $item)
                            <tr>
                                <td>{{ $srl++ }}</td>
                                <td>{{ $item['expense_head'] }}</td>
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
                            <!-- EXPENSE ACCOUNTS-->
                            <tr>
                                <td colspan="6" style="text-align: center"><strong>Total</strong></td>
                                <td style="text-align: right"><strong>{{ number_format($sum_cls_dr,2) }}</strong></td>
                                <td style="text-align: right"><strong>{{ number_format(abs($sum_cls_cr),2) }}</strong></td>
                            </tr>
                        </table>
                        
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- <p style="font-size: 90%; text-align: center">       
         © Copright {{date('Y')}} | All Rights Reserved To {{ $globalSettings->company_name }} | Powered By V-Link Network
    </p>   -->  
</section>
<!-- End Main Content -->
</body>
</html>