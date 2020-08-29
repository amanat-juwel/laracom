
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Account Statement Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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

</style>
<body onload="window.print();">

<!-- Main content -->
<section class="content" >
    <div class="">
        <div class="">
            <div class="row" style="margin-right: 3%; margin-left: 3%;margin-top: 3%">
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    <p>{{ $account_name }} A/C Statement</p>
                    <p><b>From: </b>{{date('d-m-Y', strtotime($start_date))}}, <b>To: </b>{{date('d-m-Y', strtotime($end_date))}}</p>
                </center>
                <!-- START CASH/BANK/OTHER -->
                @if($account_id[0]=='G' )
                <div class="col-md-12">
                    <div class=""> 
                       <div class=""> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; width: 4%; border-color: black;">Srl</th>
                                            <th style="text-align:left; width: 12%; border-color: black;">Date</th>
                                            <th style="text-align:center; width: 37%; border-color: black;">Particular</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Debit</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Credit</th>
                                            <th style="text-align:center; width: 17%; border-color: black;">Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>{{ 1 }}</td>
                                            <td>@php 
                                                    echo $previous_date = date('d-m-Y', strtotime($start_date . " - 1 day")); 
                                                    $bal = $opening_balance;
                                                @endphp
                                            </td>
                                            <td>Opening Balance</td>
                                            <td style="text-align:right">@if($opening_balance>=0){{ number_format($opening_balance,2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">@if($opening_balance<=0){{ number_format(abs($opening_balance),2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                        </tr>
                                        @php
                                        if($opening_balance>=0) $sum_debit = $opening_balance; else $sum_debit = 0;
                                        if($opening_balance< 0) $sum_credit = abs($opening_balance); else $sum_credit = 0;
                                        $transaction_debit = 0;
                                        $transaction_credit = 0;
                                        @endphp
           
                                        @if(isset($all_transactions))
                                            @foreach($all_transactions as $key => $single_transaction)
                                            <tr>
                                                <td>{{ ++$key+1}}</td>
                                                <td>{{ date('d-m-Y', strtotime($single_transaction->transaction_date)) }}</td>
                                                <td>{{ $single_transaction->transaction_description }} <b>Ref: </b>{{ $single_transaction->voucher_ref }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->deposit,2) }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->expense,2) }}</td>
                                                @php $bal += ($single_transaction->deposit - $single_transaction->expense);
                                                @endphp 

                                                <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                            </tr>
                                            @php $sum_debit += $single_transaction->deposit; $sum_credit += $single_transaction->expense; 
                                            $transaction_debit += $single_transaction->deposit;
                                            $transaction_credit += $single_transaction->expense; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <th colspan="3" style="text-align:center">TOTAL</th>
                                            <th style="text-align:right">{{ number_format($sum_debit,2) }}</th>
                                            <th style="text-align:right">{{ number_format($sum_credit,2) }}</th>
                                            <th style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <h4>Transaction Balance for the period From {{$start_date}} To {{$end_date}} : <strong>@if($transaction_debit-$transaction_credit >= 0){{number_format($transaction_debit-$transaction_credit,2)}} Dr
                                @else {{number_format($transaction_credit-$transaction_debit,2)}} Cr @endif</strong>
                                </h4>
                            </div>
                        </div>
                    </div>    
                </div>
                @endif
                <!-- END CASH/BANK/OTHER -->
                <!-- START CUSTOMER/SUPPLIER LEDGER-->
                @if($account_id[0]=='S' || $account_id[0]=='C' )
                <div class="col-md-12 ">
                   <div class=""> 
                       <div class=""> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; width: 4%; border-color: black;">Srl</th>
                                            <th style="text-align:left; width: 12%; border-color: black;">Date</th>
                                            <th style="text-align:center; width: 37%; border-color: black;">Particular</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Debit</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Credit</th>
                                            <th style="text-align:center; width: 17%; border-color: black;">Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>{{ 1 }}</td>
                                            <td>@php 
                                                    echo $previous_date = date('d-m-Y', strtotime($start_date . " - 1 day")); 
                                                    $bal = $opening_balance;
                                                @endphp
                                            </td>
                                            <td>Opening Balance</td>
                                            <td style="text-align:right">@if($opening_balance>=0){{ number_format($opening_balance,2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">@if($opening_balance<=0){{ number_format(abs($opening_balance),2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                        </tr>
                                        @php
                                        if($opening_balance>=0) $sum_debit = $opening_balance; else $sum_debit = 0;
                                        if($opening_balance< 0) $sum_credit = abs($opening_balance); else $sum_credit = 0;
                                        $transaction_debit = 0;
                                        $transaction_credit = 0;
                                        @endphp
           
                                        @if(isset($all_transactions))
                                            @foreach($all_transactions as $key => $single_transaction)
                                            <tr>
                                                <td>{{ ++$key+1}}</td>
                                                <td>{{ date('d-m-Y', strtotime($single_transaction->transaction_date)) }}</td>
                                                <td>{{ $single_transaction->particulars }} <b>Ref: </b>{{ $single_transaction->voucher_ref }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->debit,2) }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->credit,2) }}</td>
                                                @php $bal += ($single_transaction->debit - $single_transaction->credit);
                                                @endphp 

                                                <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                            </tr>
                                            @php $sum_debit += $single_transaction->debit; $sum_credit += $single_transaction->credit; 
                                            $transaction_debit += $single_transaction->debit;
                                            $transaction_credit += $single_transaction->credit; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <th colspan="3" style="text-align:center">TOTAL</th>
                                            <th style="text-align:right">{{ number_format($sum_debit,2) }}</th>
                                            <th style="text-align:right">{{ number_format($sum_credit,2) }}</th>
                                            <th style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <h4>Transaction Balance for the period From {{$start_date}} To {{$end_date}} : <strong>@if($transaction_debit-$transaction_credit >= 0){{number_format($transaction_debit-$transaction_credit,2)}} Dr
                                @else {{number_format($transaction_credit-$transaction_debit,2)}} Cr @endif</strong>
                                </h4>
                            </div>
                       </div>
                   </div>
                </div>
                @endif
                <!-- END SUPPLIER LEDGER-->
                
                <!-- START INCOME LEDGER-->
                @if($account_id[0]=='I')
                <div class="col-md-12 ">
                   <div class=""> 
                       <div class=""> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; width: 4%; border-color: black;">Srl</th>
                                            <th style="text-align:left; width: 12%; border-color: black;">Date</th>
                                            <th style="text-align:center; width: 37%; border-color: black;">Particular</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Debit</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Credit</th>
                                            <th style="text-align:center; width: 17%; border-color: black;">Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>{{ 1 }}</td>
                                            <td>@php 
                                                    echo $previous_date = date('d-m-Y', strtotime($start_date . " - 1 day")); 
                                                    $bal = $opening_balance;
                                                @endphp
                                            </td>
                                            <td>Opening Balance</td>
                                            <td style="text-align:right">@if($opening_balance>=0){{ number_format($opening_balance,2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">@if($opening_balance<=0){{ number_format(abs($opening_balance),2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                        </tr>
                                        @php
                                        if($opening_balance>=0) $sum_debit = $opening_balance; else $sum_debit = 0;
                                        if($opening_balance< 0) $sum_credit = abs($opening_balance); else $sum_credit = 0;
                                        $transaction_debit = 0;
                                        $transaction_credit = 0;
                                        @endphp
           
                                        @if(isset($all_transactions))
                                            @foreach($all_transactions as $key => $single_transaction)
                                            <tr>
                                                <td>{{ ++$key+1}}</td>
                                                <td>{{ date('d-m-Y', strtotime($single_transaction->date)) }}</td>
                                                <td>{{ $single_transaction->description }} <b>Ref: </b>{{ $single_transaction->voucher_ref }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->reverse_amount,2) }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->amount,2) }}</td>
                                                @php $bal += ($single_transaction->reverse_amount - $single_transaction->amount);
                                                @endphp 

                                                <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                            </tr>
                                            @php $sum_debit += $single_transaction->reverse_amount; $sum_credit += $single_transaction->amount; 
                                            $transaction_debit += $single_transaction->reverse_amount;
                                            $transaction_credit += $single_transaction->amount; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <th colspan="3" style="text-align:center">TOTAL</th>
                                            <th style="text-align:right">{{ number_format($sum_debit,2) }}</th>
                                            <th style="text-align:right">{{ number_format($sum_credit,2) }}</th>
                                            <th style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </th>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                                <h4>Transaction Balance for the period From {{$start_date}} To {{$end_date}} : <strong>@if($transaction_debit-$transaction_credit >= 0){{number_format($transaction_debit-$transaction_credit,2)}} Dr
                                @else {{number_format($transaction_credit-$transaction_debit,2)}} Cr @endif</strong>
                                </h4>
                            </div>
                       </div>
                   </div>
                </div>
                @endif
                <!-- END INCOME LEDGER-->

                <!-- START EXPENSE LEDGER-->
                @if($account_id[0]=='E')
                <div class="col-md-12 ">
                   <div class=""> 
                       <div class=""> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; width: 4%; border-color: black;">Srl</th>
                                            <th style="text-align:left; width: 12%; border-color: black;">Date</th>
                                            <th style="text-align:center; width: 37%; border-color: black;">Particular</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Debit</th>
                                            <th style="text-align:center; width: 15%; border-color: black;">Credit</th>
                                            <th style="text-align:center; width: 17%; border-color: black;">Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>{{ 1 }}</td>
                                            <td>@php 
                                                    echo $previous_date = date('d-m-Y', strtotime($start_date . " - 1 day")); 
                                                    $bal = $opening_balance;
                                                @endphp
                                            </td>
                                            <td>Opening Balance</td>
                                            <td style="text-align:right">@if($opening_balance>=0){{ number_format($opening_balance,2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">@if($opening_balance<=0){{ number_format(abs($opening_balance),2) }} @else 0.00 @endif</td>
                                            <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                        </tr>
                                        @php
                                        if($opening_balance>=0) $sum_debit = $opening_balance; else $sum_debit = 0;
                                        if($opening_balance< 0) $sum_credit = abs($opening_balance); else $sum_credit = 0;
                                        $transaction_debit = 0;
                                        $transaction_credit = 0;
                                        @endphp
           
                                        @if(isset($all_transactions))
                                            @foreach($all_transactions as $key => $single_transaction)
                                            <tr>
                                                <td>{{ ++$key+1}}</td>
                                                <td>{{ date('d-m-Y', strtotime($single_transaction->date)) }}</td>
                                                <td>{{ $single_transaction->description }} <b>Ref: </b>{{ $single_transaction->voucher_ref }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->amount,2) }}</td>
                                                <td style="text-align:right">{{ number_format($single_transaction->reverse_amount,2) }}</td>
                                                @php $bal += ($single_transaction->amount - $single_transaction->reverse_amount);
                                                @endphp 

                                                <td style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </td>
                                            </tr>
                                            @php $sum_debit += $single_transaction->amount; $sum_credit += $single_transaction->reverse_amount; 
                                            $transaction_debit += $single_transaction->amount;
                                            $transaction_credit += $single_transaction->reverse_amount; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <th colspan="3" style="text-align:center">TOTAL</th>
                                            <th style="text-align:right">{{ number_format($sum_debit,2) }}</th>
                                            <th style="text-align:right">{{ number_format($sum_credit,2) }}</th>
                                            <th style="text-align:right">
                                                @if($bal>=0){{ number_format($bal,2) }} Dr @elseif($bal< 0) {{ number_format(abs($bal),2) }} Cr @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <h4>Transaction Balance for the period From {{$start_date}} To {{$end_date}} : <strong>@if($transaction_debit-$transaction_credit >= 0){{number_format($transaction_debit-$transaction_credit,2)}} Dr
                                @else {{number_format($transaction_credit-$transaction_debit,2)}} Cr @endif</strong>
                                </h4>
                            </div>
                       </div>
                   </div>
                </div>
                @endif
                <!-- END EXPENSE LEDGER-->
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