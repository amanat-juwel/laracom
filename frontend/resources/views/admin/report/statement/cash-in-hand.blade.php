@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        TRANSACTION BY DATE
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Transaction By Date</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">

        <div class="row">
            <form class="form" action="{{ url('admin/report/cash-in-hand') }}" id="date_form" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">   
                <div class="col-md-3">
                    <div class="form-group">
                         <label for="">For Date</label>
                         <input type="text" autocomplete="OFF" id="datepicker" name="reporting_date" class="form-control input-sm" @if(isset($reporting_date)) value="{{ $reporting_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                    <br>
                    @if(isset($reporting_date))
                    
                    <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/report/cash-in-hand-pdf/'.$reporting_date) }}" target="_blank">Print/Download as PDF</a> 
                     
                    @endif
                    </div>
                </div>  
            </form>  
             @if(isset($reporting_date))  
                <div class="col-md-12">
                <!--
                @if(isset($start_date))
                <p align="center"><b>From:</b> {{ $start_date }} <b>To:</b> {{ $end_date }}</p>
                @endif
                -->
                <div class="panel panel-default">
                <div class="panel-body">
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
                       
                            
                        </tbody>
                    </table>
                    <!-- END TODAYS SUMMARY-->
                    <!-- START TRANSACTION DETAILS-->
                    <h4><u>Transaction Details</u></h4>
                    <table class="table-bordered" width="100%">
                        <thead>
                            <tr>
                                    <th>Srl.</th>
                                    <th style="text-align:center">Date</th>
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

                    <h4><u>Account Summary</u></h4>
                    <!-- END TRANSACTION DETAILS-->
                    <!-- START ACCOUNT SUMMARY-->
                        <!-- <h4><u>Account Summary</u></h4>
                        <table class="table-bordered" width="100%">
                            <thead>
                                <tr>
                                        <th>Srl.</th>
                                        <th style="text-align:center">Account</th>
                                        <th style="text-align:center">Debit</th>
                                        <th style="text-align:center">Credit</th>
                                        <th style="text-align:center">Balance</th>
                                    </tr>
                            </thead>
w
                            <tbody>
                                    @if(isset($accounts_summary))
                                        @foreach($accounts_summary as $key => $account_summary)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $account_summary->bank_name }}</td>
                                            <td style="text-align:right">@php echo number_format($account_summary->deposit,2) @endphp</td>
                                            <td style="text-align:right">@php echo number_format($account_summary->expense,2) @endphp</td>
                                            <td style="text-align:right">
                                                @if($account_summary->deposit-$account_summary->expense > 0)
                                                {{ number_format($account_summary->deposit-$account_summary->expense,2)." Dr" }} 
                                                @else
                                                {{ number_format($account_summary->expense-$account_summary->deposit,2)." Cr" }} 
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                        </table> -->
                        <!-- END ACCOUNT SUMMARY-->
                        <!-- <table class="table table-bordered" id="purchase_details">-->                        
                        @if(isset($view_of_accounts))
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
                        @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
<!-- End Main Content -->
@endsection

<script>
    $("#submit").click(function() {   //button id
        var myForm = $("#dateform");  //form id
        myForm.submit(function(e){
        e.preventDefault();
        var formData = myForm.serialize();
            $.ajax({
                url:'{{url('report/date-to-date-sales-report')}}',
                type:'post',
                data:formData,
                success:function(data){
                //  alert('Successfully Retrived');
                //  $('#start_date,#end_date').val('');
                },
                error: function (data) {
                    alert('Something Went Wrong');
                }
            });
        });
    });    
</script>