@extends('admin.layouts.template')

@section('template')
<style type="text/css">

    td,th{
        padding: 2px;
        border: 1px solid black;
    }
    a{
        color: #fff;
    }
    .panel .panel-title>a:hover {
        color: #fff;
    }
</style>
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
<section class="content-header">
    <h1>BALANCE SHEET</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Business Overview</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
<!--         <div class="col-md-12">
            <form class="form" action="{{ url('report/balance-sheet') }}" id="date_form" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">   
                <div class="col-md-3">
                    <div class="form-group">
                         <label for="">Date</label>
                         <input type="text" autocomplete="OFF" id="datepicker" name="reporting_date" class="form-control input-sm" @if(isset($reporting_date)) value="{{ $reporting_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                    <br>
                    @if(isset($reporting_date))
                  
                    @endif
                    </div>
                </div>  
            </form>    
        </div> -->
        
        <!-- LEFT -->
        <div class="col-md-6">
            <div class="row">   
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            CASH-BANK BALANCE
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        @php $cash_bank_total = 0; @endphp
                                        @foreach($cashBank as $data)
                                        @if($data['closing_balance']!=0)
                                        <tr>
                                            <td>{{ $data['account_name'] }}</td>
                                            <td class="amount">{{ number_format($data['closing_balance'],0) }}</td>
                                        </tr>
                                        @endif
                                        @php $cash_bank_total += $data['closing_balance']; @endphp
                                        @endforeach
                                        <tr>
                                            <th>TOTAL</th>
                                            <th class="amount">{{ number_format($cash_bank_total,0) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            INVENTORY
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        <tr>
                                            <th>TOTAL</th>
                                            <th class="amount">{{ number_format($stockStore,0) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    @php $rec_total = 0; @endphp
                    @foreach($accountsReceivable as $data)
                    @php
                        $balance =  $data->debit + $data->op_bal_debit - $data->credit + $data->op_bal_credit;
                        $rec_total += $balance;
                    @endphp
                    @endforeach
                    <div class="panel-group">
                        <div class="panel panel-success">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" href="#collapse1">ACCOUNTS RECEIVABLE
                              <span class="pull-right">{{ number_format($rec_total,0) }}</span>
                              </a>
                            </h4>
                          </div>
                          <div id="collapse1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        @php $rec_total = 0; @endphp
                                        @foreach($accountsReceivable as $data)
                                        @php
                                            $balance =  $data->debit + $data->op_bal_debit - $data->credit + $data->op_bal_credit;
                                            $rec_total += $balance;
                                        @endphp
                                        @if($balance!=0)
                                        <tr>
                                            <td>{{ $data->customer_name }}</td>
                                            <td class="amount">{{ number_format($balance,0) }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        <tr>
                                            <th>TOTAL</th>
                                            <th class="amount">{{ number_format($rec_total,0) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    
                </div>

                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            TOTAL ASSET
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        <tr>
                                            <th>NET</th>
                                            <th class="amount">{{ number_format($cash_bank_total+$stockStore+$rec_total,0) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-md-6">
            <div class="row">   
                <div class="col-md-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            LIABILITIES
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        @php $loan_total = 0; @endphp
                                        @foreach($loans as $data)
                                        @if($data['closing_balance']!=0)
                                        <tr>
                                            <td>{{ $data['account_name'] }}</td>
                                            <td class="amount">{{ number_format($data['closing_balance'],0) }}</td>
                                        </tr>
                                        @endif
                                        @php $loan_total += $data['closing_balance']; @endphp
                                        @endforeach
                                        <tr>
                                            <th>TOTAL</th>
                                            <th class="amount">{{ number_format($loan_total,0) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            ACCOUNTS PAYABLE
                        </div
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        @php $pay_total = 0; @endphp
                                        @foreach($accountsPayable as $data)
                                        @php
                                            $balance = $data->credit + $data->op_bal_credit - $data->debit + $data->op_bal_debit;
                                            $pay_total += $balance;
                                        @endphp
                                       
                                        <tr>
                                            <td>{{ $data->sup_name }}</td>
                                            <td class="amount">{{ number_format($balance,0) }}</td>
                                        </tr>
                                      
                                        @endforeach
                                        <tr>
                                            <th>TOTAL</th>
                                            <th class="amount">{{ number_format($pay_total,0) }}</th>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            TOTAL LIABILITIES
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table-bordered" width="100%">
                                    <tbody>
                                        <tr>
                                            <th>NET</th>
                                            <th class="amount">{{ number_format($loan_total+$pay_total,0) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- .RIGHT -->
            </div>
        </div>
    </div>
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