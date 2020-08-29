
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Account Statement Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
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
<style>
@media print
{
  table { page-break-after:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
  td    { page-break-inside:avoid; page-break-after:auto }
  thead { display:table-header-group }
  tfoot { display:table-footer-group }
}
</style>
<style type="text/css">
  h2{
    margin-bottom: 0px;
  }

  .border-top{
    border-top: 1px solid black;
  }

  table.table-bordered{
    border:1px solid black;
    margin-top:20px;
  }
  table.table-bordered > thead > tr > th{
    border:1px solid black;
  }
  table.table-bordered > tbody > tr > td{
    border:1px solid black;
  }

  h3,p{
    margin: 1px;
  }
  th,td{
    padding: 2px;
  }
</style>
<body onload="window.print();">

<!-- Main content -->
<section class="content" >
    <div class="">
        <div class="">
            <div class="row" style="margin-right: 3%; margin-left: 3%;margin-top: 3%">
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    <p>Daily Cash Sheet</p>
                    <p>Ledger: <b>{{ $bank_name }}<b></p>
                    <p><b></b>{{ date('M.d,Y', strtotime($start_date)) }} <b>To: </b>{{ date('M.d,Y', strtotime($end_date)) }}</p>
                </center>
                <!-- START  -->
             
                <div class="col-md-12">
                    <div class=""> 
                       <div class=""> 
                            <div class="">

                               
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left">Date</th>
                                            <th style="text-align:left;">Particulars</th>
                                            <th style="text-align:left">Voucher No.</th>
                                            <th style="text-align:center">Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td colspan="4">&nbsp</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-bold">Opening Balance</td>
                                            @php $bal = $opening_balance; @endphp
                                            <td class="amount text-bold">@if($opening_balance>=0){{ number_format($opening_balance,2) }} @elseif($opening_balance < 0) ({{ number_format(abs($opening_balance),2) }}) @else 0.00 @endif</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp</td>
                                        </tr>
                                    </tbody>

                                    <!-- START RECEIPT -->    
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-bold">Add: Receipt</td>
                                        </tr>

                                        @php
                                        if($opening_balance>=0) $sum_deposit = $opening_balance; else $sum_deposit = 0;
                                        if($opening_balance< 0) $sum_expense = abs($opening_balance); else $sum_expense = 0;
                                        $transaction_deposit = 0;
                                        $transaction_expense = 0;
                                        @endphp
                                        
                                        
                                        @if(isset($receipts))
                                            @foreach($receipts as $key => $single_transaction)
                                            <tr>
                                                <td>{{ date('M.d,Y', strtotime($single_transaction->transaction_date)) }}</td>
                                                <td>{{ $single_transaction->transaction_description }}</td>
                                                <td>{{ $single_transaction->voucher_ref }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->deposit,2) }}</td>
                                              
                                                
                                            </tr>
                                            @php $sum_deposit += $single_transaction->deposit; $sum_expense += $single_transaction->expense; 
                                            $transaction_deposit += $single_transaction->deposit;
                                            $transaction_expense += $single_transaction->expense; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td colspan="4">&nbsp</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align:center">Total Receipt</td>
                                            <td class="amount text-bold">
                                                @if($transaction_deposit>=0){{ number_format($transaction_deposit,2) }}  @elseif($transaction_deposit< 0) ({{ number_format(abs($transaction_deposit),2) }})  @endif
                                            </td>
                                        </tr>
                                        
                                        @php $sub_total = $opening_balance + $transaction_deposit; @endphp
                                        <tr>
                                            <td colspan="3" style="text-align:center">Sub Total</td>
                                            <td class="amount text-bold">
                                                @if($sub_total >=0){{ number_format($sub_total,2) }} @elseif($sub_total< 0) ({{ number_format(abs($sub_total),2) }}) @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp</td>
                                        </tr>
                                    </tbody>
                                    <!-- END RECEIPT --> 

                                    <!-- START PAYMENT -->    
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-bold">Less: Payment</td>
                                        </tr>
                                        @php
                                        if($opening_balance>=0) $sum_deposit = $opening_balance; else $sum_deposit = 0;
                                        if($opening_balance< 0) $sum_expense = abs($opening_balance); else $sum_expense = 0;
                                        $transaction_deposit = 0;
                                        $transaction_expense = 0;
                                        @endphp
                                        
                                        
                                        @if(isset($payments))
                                            @foreach($payments as $key => $single_transaction)
                                            <tr>
                                                <td>{{ date('M.d,Y', strtotime($single_transaction->transaction_date)) }}</td>
                                                <td>{{ $single_transaction->transaction_description }}</td>
                                                <td>{{ $single_transaction->voucher_ref }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->expense,2) }}</td>
                                              
                                                
                                            </tr>
                                            @php $sum_deposit += $single_transaction->deposit; $sum_expense += $single_transaction->expense; 
                                            $transaction_deposit += $single_transaction->deposit;
                                            $transaction_expense += $single_transaction->expense; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="text-align:center">Total Payment</td>
                                            <td class="amount text-bold">
                                                @if($transaction_expense>=0){{ number_format($transaction_expense,2) }}  @elseif($transaction_expense< 0) ({{ number_format(abs($transaction_expense),2) }})  @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">&nbsp</td>
                                        </tr>
                                        @php 
                                            $closing = $sub_total - $transaction_expense;
                                        @endphp
                                        <tr>
                                            <td colspan="3" class="text-bold">Closing Balance</td>
                                            <td class="amount text-bold">
                                                @if($closing>=0){{ number_format($closing,2) }} @elseif($closing< 0) ({{ number_format(abs($closing),2) }}) @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                    <!-- END PAYMENT -->    
                                </table>


                               
                            </div>
                        </div>
                    </div>    
                </div>
               
                <!-- END  -->
            </div>
        </div>
    </div>

</section>
<!-- End Main Content -->
</body>
</html>