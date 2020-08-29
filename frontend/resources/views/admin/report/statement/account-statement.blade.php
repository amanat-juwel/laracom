@extends('admin.layouts.template')
   

@section('template')
<!-- Content Header -->
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
<section class="content-header">
    <h1>
        @if(isset($account_name)){{ $account_name}}@endif A/C Statement
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Account Statement</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <form class="form" action="{{ url('admin/report/account-statement') }}" name="myForm" id="date_form" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Account</label>
                        <div class="col-sm-12">
                        <select name="account_id" class="form-control select2" required>
                            <option value="">--- Choose an Account ---</option>
                            <optgroup label="Cash/Bank/Other">
                                @foreach($accounts as $data)
                                <option value="G{{ $data->bank_account_id }}" @if(isset($account_id)) @if($account_id=="G".$data->bank_account_id) selected @endif @endif >{{ $data->bank_name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Account Receivable">
                                @foreach($customers as $data)
                                <option value="C{{ $data->customer_id }}" @if(isset($account_id)) @if($account_id=="C".$data->customer_id) selected @endif @endif>{{ $globalSettings->invoice_prefix."-".str_pad($data->customer_code, 8, '0', STR_PAD_LEFT) }} {{ $data->customer_name }}
                                </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Account Payable">
                                @foreach($suppliers as $data)
                                <option value="S{{ $data->supplier_id }}" @if(isset($account_id)) @if($account_id=="S".$data->supplier_id) selected @endif @endif>{{ $data->sup_name }}</option>
                                @endforeach
                            </optgroup>
                          
                        </select>
                        </div>
                    </div>    
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="">From</label>
                        <input type="text" id="datepicker" autocomplete="off" name="start_date" id="start_date" class="form-control input-sm" @if(isset($start_date)) value="{{ $start_date }}"@endif  required/>
                    </div>
                </div>    
                <div class="col-md-2">
                    <div class="form-group">
                         <label for="">To</label>
                         <input type="text" id="datepicker2" autocomplete="off" name="end_date" id="end_date" class="form-control input-sm" @if(isset($end_date)) value="{{ $end_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                         <br>
                         <input type="submit"  class="btn btn-success btn-sm" value="Submit" />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                    <br>
                    @if(isset($start_date))
                    <br>
                    <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/report/account-statement-pdf/'.$start_date.'/'.$end_date.'/'.$account_id) }}" target="_blank">Print/Download as PDF</a>     
                    @endif
                    </div>
                </div>  
            </form>    
                @if(isset($account_id))
                <!-- START CASH/BANK/OTHER -->
                @if($account_id[0]=='G' )
                <div class="col-md-12">
                    <div class="panel panel-primary"> 
                       <div class="panel-body"> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left;">Srl</th>
                                            <th style="text-align:left">Date</th>
                                            <th style="text-align:left">JV-Ref</th>
                                            <th style="text-align:center">Particular</th>
                                            <th style="text-align:center">Debit</th>
                                            <th style="text-align:center">Credit</th>
                                            <th style="text-align:center">Balance</th>
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
                                            <td>---</td>
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
                                                <td><a target="_blank" href="{{url('/journal/'.$single_transaction->voucher_ref.'/edit')}}">{{ $single_transaction->voucher_ref }}</a></td>
                                                <td>{{ $single_transaction->transaction_description }}</td>
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
                                            <th colspan="4" style="text-align:center">TOTAL</th>
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
                   <div class="panel panel-primary"> 
                       <div class="panel-body"> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left;">Srl</th>
                                            <th style="text-align:left">Date</th>
                                            <th style="text-align:left">JV-Ref</th>
                                            <th style="text-align:center">Particular</th>
                                            <th style="text-align:center">Debit</th>
                                            <th style="text-align:center">Credit</th>
                                            <th style="text-align:center">Balance</th>
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
                                            <td>---</td>
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
                                                <td><a target="_blank" href="{{url('/journal/'.$single_transaction->voucher_ref.'/edit')}}">{{ $single_transaction->voucher_ref }}</a></td>
                                                <td>{{ $single_transaction->particulars }}</td>
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
                                            <th colspan="4" style="text-align:center">TOTAL</th>
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
                   <div class="panel panel-primary"> 
                       <div class="panel-body"> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left;">Srl</th>
                                            <th style="text-align:left">Date</th>
                                            <th style="text-align:left">JV-Ref</th>
                                            <th style="text-align:center">Particular</th>
                                            <th style="text-align:center">Debit</th>
                                            <th style="text-align:center">Credit</th>
                                            <th style="text-align:center">Balance</th>
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
                                            <td>---</td>
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
                                                <td><a target="_blank" href="{{url('/journal/'.$single_transaction->voucher_ref.'/edit')}}">{{ $single_transaction->voucher_ref }}</a></td>
                                                <td>{{ $single_transaction->description }}</td>
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
                                            <th colspan="4" style="text-align:center">TOTAL</th>
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
                   <div class="panel panel-primary"> 
                       <div class="panel-body"> 
                            <div class="table-responsive">
                                <!-- <table class="table table-bordered" id="purchase_details">-->                        
                                <table class="table-bordered" id="" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left;">Srl</th>
                                            <th style="text-align:left">Date</th>
                                            <th style="text-align:left">JV-Ref</th>
                                            <th style="text-align:center">Particular</th>
                                            <th style="text-align:center">Debit</th>
                                            <th style="text-align:center">Credit</th>
                                            <th style="text-align:center">Balance</th>
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
                                            <td>---</td>
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
                                                <td><a target="_blank" href="{{url('/journal/'.$single_transaction->voucher_ref.'/edit')}}">{{ $single_transaction->voucher_ref }}</a></td>
                                                <td>{{ $single_transaction->description }}</td>
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
                                            <th colspan="4" style="text-align:center">TOTAL</th>
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
            @endif
        </div>
    </div>

<script type="text/javascript">
document.forms['myForm'].elements['bank_account_id'].value="@if(isset($bank_account_id)){{$bank_account_id}}@endif";

$(document).ready(function () {
    $("#start_date").change(function () {
       $('#end_date').val('');
    });
});  
  
</script>    

</section>
<!-- End Main Content -->
@endsection

