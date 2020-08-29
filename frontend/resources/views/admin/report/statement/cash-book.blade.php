@extends('admin.layouts.template')

@section('template')
<style type="text/css">

    td{
        padding: 2px;
        border: 1px solid black;
    }
    table.table-bordered{
    border:1px solid black;
    }
   table.table-bordered > thead > tr > th{
    border:1px solid black;
    text-align: center;
    padding: 2px;
   }
   table.table-bordered > tbody > tr > td{
    border:1px solid black;
   }
</style>
<section class="content-header">
    <h1>
        CASH BOOK
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Cash Book</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="form" action="{{ url('admin/report/statement/cash-book') }}" id="date_form" method="post">
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
                    
                    <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/report/statement/cash-book-pdf/'.$reporting_date) }}" target="_blank">Print/Download as PDF</a> 
                     
                    @endif
                    </div>
                </div>  
            </form>    
        </div>
        @if(isset($accountGroups))
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
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
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
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
            </div>
        </div>
        @endif
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