<!DOCTYPE html>
<html lang="en">
<head>
  <title> Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
</head>
<style type="text/css" media="print">
    @page 
    {
        size: A4 Landscape;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
<style type="text/css">
  h3{
    padding: 0px;
    margin-top: 55px;
    margin-bottom: 0px;
  }
    td{
        padding-left: 2px;
        padding-right: 2px;
        border: 1px solid black;
    }
    table.table-bordered{
    border:1px solid black;
    }
   table.table-bordered > thead > tr > th{
    border:1px solid black;
    text-align: center;
    padding-left: 2px;

   }
   table.table-bordered > tbody > tr > td{
    border:1px solid black;
   }
</style>
<body onload="window.print();">

<!-- Main content -->
<section class="" >
    <div class="" style="margin-left:20px;margin-right: 20px;">
        <div class="">
            <div class="row">
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    <b>CASH BOOK</b><br>
                    <b>Date: </b>{{date('d-m-Y',strtotime($reporting_date))}}<br><br>
                </center>
                
                    
                <div class="col-xs-6" style="padding-right: 0px ; margin-right: 0px;">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>V.R#</th>
                                    <th>Particulars</th>
                                    @foreach($accountGroups as $data)
                                    <th>{{$data->bank_name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tr>
                                <td></td>
                                <td>Opening Balance</td>
                                @for($i=0; $i<count($opening_balance_array); $i++)
                                @if($opening_balance_array[$i]['opening_balance']>=0)
                                <td class="amount">{{number_format($opening_balance_array[$i]['opening_balance'],2)}}</td>
                                @else
                                <td class="amount">({{number_format(abs($opening_balance_array[$i]['opening_balance']),2)}})</td>
                                @endif
                                @endfor
                            </tr>

                            @php 
                                $grp_vr = '';
                            @endphp
                            @foreach($group_account_ledgers_income as $ledger)
                            <tr>

                                @if($grp_vr!="$ledger->voucher_ref")
                                <td>{{$grp_vr="$ledger->voucher_ref"}}</td>
                                @else
                                <td></td>
                                @endif
                                <td>{{$ledger->transaction_description}}</td>
                                @foreach($accountGroups as $data)
                                
                                @if("$ledger->bank_name" == "$data->bank_name")
                                <td class="amount">{{number_format($ledger->deposit,2)}}</td>
                                @else
                                <td></td>
                                @endif

                                @endforeach
                                
                            </tr>
                            @endforeach

                            @php $val = 1+count($group_account_ledgers_expense)-count($group_account_ledgers_income) @endphp
                            @for($k=1; $k<=$val; $k++)
                            <tr>
                                <td>&nbsp</td>
                                <td></td>
                                @foreach($accountGroups as $data)
                                    <td></td>
                                @endforeach
                            </tr>
                            @endfor

                            <tr>
                                <th style="border:1px solid black; text-align: center;" colspan="2">
                                    Total TK.
                                </th>
                                @for($i=0; $i<count($income_balance_array); $i++)
                                @if($income_balance_array[$i]['income_balance']>=0)
                                <th class="amount" style="border:1px solid black;">{{number_format($income_balance_array[$i]['income_balance'],2)}}</th>
                                @else
                                <th class="amount" style="border:1px solid black;">({{number_format(abs($income_balance_array[$i]['income_balance']),2)}})</th>
                                @endif
                                @endfor
                            </tr>

                        </table>
                         
                    </div>
                </div>
                <div class="col-xs-6" style="padding-left: 0px; margin-left: 0px;">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>V.R#</th>
                                    <th>Particulars</th>
                                    @foreach($accountGroups as $data)
                                    <th>{{$data->bank_name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            @php 
                                $grp_vr = '';
                            @endphp
                            @foreach($group_account_ledgers_expense as $ledger)
                            <tr>
                                @if($grp_vr!="$ledger->voucher_ref")
                                <td>{{$grp_vr="$ledger->voucher_ref"}}</td>
                                @else
                                <td></td>
                                @endif

                                <td>{{$ledger->transaction_description}}</td>
                                @foreach($accountGroups as $data)
                                
                                @if("$ledger->bank_name" == "$data->bank_name")
                                <td class="amount">{{number_format($ledger->expense,2)}}</td>
                                @else
                                <td></td>
                                @endif

                                @endforeach
                                
                            </tr>
                            @endforeach
                            <tr>
                                <th style="border:1px solid black; text-align: center;" colspan="2">
                                    Total Expense.
                                </th>
                                @for($i=0; $i<count($expense_balance_array); $i++)
                                @if($expense_balance_array[$i]['expense_balance']>=0)
                                <th class="amount" style="border:1px solid black;">{{number_format($expense_balance_array[$i]['expense_balance'],2)}}</th>
                                @else
                                <th class="amount" style="border:1px solid black;">({{number_format(abs($expense_balance_array[$i]['expense_balance']),2)}})</th>
                                @endif
                                @endfor
                            </tr>

                            @php $val = count($group_account_ledgers_income)-count($group_account_ledgers_expense) @endphp
                            @for($k=1; $k<$val; $k++)
                            <tr>
                                <td>&nbsp</td>
                                <td></td>
                                @foreach($accountGroups as $data)
                                    <td></td>
                                @endforeach
                            </tr>
                            @endfor

                            <tr>
                                <th style="border:1px solid black; text-align: center;" colspan="2">
                                    Closing Balance.
                                </th>
                                @for($i=0; $i<count($closing_balance_array); $i++)
                                @if($closing_balance_array[$i]['closing_balance']>=0)
                                <th class="amount" style="border:1px solid black;">{{number_format($closing_balance_array[$i]['closing_balance'],2)}}</th>
                                @else
                                <th class="amount" style="border:1px solid black;">({{number_format(abs($closing_balance_array[$i]['closing_balance']),2)}})</th>
                                @endif
                                @endfor
                            </tr>

                            <tr>
                                <th style="border:1px solid black; text-align: center;" colspan="2">
                                    Total TK.
                                </th>
                                @for($i=0; $i<count($income_balance_array); $i++)
                                @if($income_balance_array[$i]['income_balance']>=0)
                                <th class="amount" style="border:1px solid black;">{{number_format($income_balance_array[$i]['income_balance'],2)}}</th>
                                @else
                                <th class="amount" style="border:1px solid black;">({{number_format(abs($income_balance_array[$i]['income_balance']),2)}})</th>
                                @endif
                                @endfor
                            </tr>

                        </table>
                    </div>
                </div>
                
                <div>
                    <div class="col-xs-4" style="text-align: center;">
                        <br><br>
                        Accountant
                    </div>
                    <div class="col-xs-4" style="text-align: center;">
                        <br><br>
                        Accountants Manager
                    </div>
                    <div class="col-xs-4" style="text-align: center;">
                        <br><br>
                        Managing Director
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

</body>
</html>