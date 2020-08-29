@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        TRIAL BALANCE
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Trial Balance Statement</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <form class="form" action="{{ url('admin/report/trial-balance') }}" name="myForm" id="date_form" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">From</label>
                            <input type="text" autocomplete="OFF" id="datepicker" name="start_date" id="start_date" class="form-control input-sm" @if(isset($start_date)) value="{{ $start_date }}"@endif  required/>
                        </div>
                    </div>    
                    <div class="col-md-3">
                        <div class="form-group">
                             <label for="">To</label>
                             <input type="text" autocomplete="OFF"  id="datepicker2" name="end_date" id="end_date" class="form-control input-sm" @if(isset($end_date)) value="{{ $end_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <br>
                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                    </div>

                </form>   
            </div>
            <div class="row">     
                <div class="col-md-12">
                    @if(isset($start_date))
                        <a class="btn btn-warning btn-xs" href="{{ url('admin/report/trial-balance-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a> 
                        
                        @endif
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

