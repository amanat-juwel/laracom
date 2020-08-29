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
        Daily Cash Sheet : @if(isset($bank_name)){{ $bank_name}}@endif Ac.
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Daily Cash Sheet</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <form class="form" action="{{ url('admin/reports/daily-cash-sheet') }}" name="myForm" id="date_form" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Account</label>
                        <div class="col-sm-12">
                        <select name="bank_account_id" class="form-control select2" required>
                            <option value="">--- Choose an Account ---</option>
                            <optgroup label="">
                                @foreach($cash_bank_accounts as $data)
                                <option value="{{ $data->bank_account_id }}" @if(isset($bank_account_id)) @if($bank_account_id == $data->bank_account_id) selected @endif @endif >{{ $data->bank_name }}</option>
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
                    <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/reports/daily-cash-sheet/'.$start_date.'/'.$end_date.'/'.$bank_account_id) }}" target="_blank">Print/Download as PDF</a>     
                    @endif
                    </div>
                </div>  
            </form>    
                @if(isset($start_date))
                <!-- START  -->
                <div class="col-md-12">
                    <div class="panel panel-primary"> 
                       <div class="panel-body"> 
                            <div class="table-responsive">

                               
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
               
                <!-- END -->
                
            @endif
        </div>
    </div>

<script type="text/javascript">
document.forms['myForm'].elements['account_id'].value="@if(isset($account_id)){{$account_id}}@endif";

$(document).ready(function () {
    $("#start_date").change(function () {
       $('#end_date').val('');
    });
});  
  
</script>    

</section>
<!-- End Main Content -->
@endsection

