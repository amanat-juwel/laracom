@extends('admin.layouts.template')

@section('template')
<script type="text/javascript">
    function myFunction(){
        window.print();
    }
</script>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
    @media print{
       .noprint{
           display:none;
       }
    }
</style>
<!-- Content Header -->
<section class="content-header">
    <h1>SALES REPORT - REFERENCE WISE</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Sales Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">  
        <div class="d-print-none noprint">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <form class="form" action="{{ url('admin/report/executive-wise-date-to-date-sales-report') }}" id="date_form" method="post">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                     <br>
                                     <input type="submit" class="btn btn-success btn-sm" value="Submit" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <br>
                                @if(isset($start_date))
                                <!-- <a class="btn btn-warning btn-xs pull-right" href="{{ url('/report/memo-wise-date-to-date-sales-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>  -->    
                                
                                    <button id="print" class="btn btn-warning btn-xs noprint pull-right" onclick="myFunction()">Print</button>
                                
                                @endif
                                </div>
                            </div>  
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        @if(isset($date_to_date_sales))
        <div class="col-md-12">  
            <div class="panel panel-primary">
                <div class="panel-heading">
                    List Sales
                </div>
                <div class="panel-body">
                       
                <!--
                @if(isset($start_date))
                <p align="center"><b>From:</b> {{ $start_date }} <b>To:</b> {{ $end_date }}</p>
                @endif
                -->
                <div class="table-responsive">
                    <table class="table-bordered" width="100%" id="purchase_details">
                            <thead>
                                <tr>
                                    <th height="25">Sl No.</th>
                                    <th>Executive</th>
                                    <th style="text-align: center">Showing Price (Total)</th>
                                    <th style="text-align: center">Discount (Total)</th>
                                    <th style="text-align: center">Deposit (Total)</th>
                                    <th style="text-align: center">Due (Total)</th>
                                </tr>

                            </thead>

                            <tbody>
                                @php $net_sales=0; $net_discount=0; $net_paid=0; $net_due=0;
                                @endphp
                                @foreach($date_to_date_sales as $key => $data)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $data->employee }}</td>
                                        <td style="text-align: right">{{ number_format($data->memo_total,2) }}</td>
                                        <td style="text-align: right">{{ number_format($data->discount,2) }}</td>
                                        <td style="text-align: right">{{ number_format($data->advanced_amount,2) }}</td>
                                        <td style="text-align: right">{{ number_format($data->memo_total-$data->discount-$data->advanced_amount,2) }}</td>
                                    </tr>
                                    @php 
                                        $net_sales += $data->memo_total;
                                        $net_discount += $data->discount;
                                        $net_paid += $data->advanced_amount;
                                        $net_due += ($data->memo_total-$data->discount-$data->advanced_amount);
                                    @endphp
                                @endforeach
                                <tr>
                                    <th height="25" colspan="2" style="text-align: center">TOTAL</th>
                                    <th style="text-align: right">{{ number_format($net_sales,2) }}</th>
                                    <th style="text-align: right">{{ number_format($net_discount,2) }}</th>
                                    <th style="text-align: right">{{ number_format($net_paid,2) }}</th>
                                    <th style="text-align: right">{{ number_format($net_due,2) }}</th>

                                </tr>
                            </tbody>
                        </table>
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