@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>INCOME REPORT</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Income Report</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">    
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/date-to-date-income-report') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">From</label>
                                <input type="text" autocomplete="OFF" id="datepicker" name="start_date" class="form-control input-sm" @if(isset($start_date)) value="{{ $start_date }}"@endif  required/>
                            </div>
                        </div>    
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label for="">To</label>
                                 <input type="text" autocomplete="OFF" id="datepicker2" name="end_date" class="form-control input-sm" @if(isset($end_date)) value="{{ $end_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                 <br>
                                 <input type="submit"  class="btn btn-success btn-sm" value="Submit" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <br>
                            @if(isset($start_date))
                            <a class="btn btn-warning btn-xs pull-right" href="{{ url('/report/date-to-date-income-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                            @endif
                            </div>
                        </div>  
                    </form>
                </div>
            </div>        
        </div>
        @if(isset($date_to_date_incomes))
        <div class="col-md-12">
                <!--
                @if(isset($start_date))
                <p align="center"><b>From:</b> {{ $start_date }} <b>To:</b> {{ $end_date }}</p>
                @endif
                -->
                <div class="panel panel-primary">
                <div class="panel-heading">
                    List Income
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table-bordered" width="100%" id="purchase_details">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <th style="text-align:left">Date</th>
                                    <th style="text-align:left">Voucher Ref</th>
                                    <th style="text-align:left">Head</th>
                                    <th style="text-align:left">Particulars</th>
                                    <th style="text-align:left">Amount</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                
                                @foreach($date_to_date_incomes as $key => $data)
                                <tr>
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ $data->date }}</td>
                                    <td>{{ $data->voucher_ref }}</td>
                                    <td>{{ $data->income_head }}</td>
                                    <td>{{ $data->description }}</td>
                                    <td>{{ $data->amount }}</td>
                                </tr>
                                @php $total += $data->amount; @endphp
                                @endforeach
                                <tr>
                                    <th colspan="5">TOTAL</th>
                                    <th style="text-align:right">{{ number_format($total,2) }}</th>

                                </tr>    
                            </tbody>
                        </table>    
                        <!-- <p class="text text-danger pull-right">Total: {{ number_format($total,2) }}</p> -->
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