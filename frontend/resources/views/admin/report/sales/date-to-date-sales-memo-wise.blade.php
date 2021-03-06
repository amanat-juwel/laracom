@extends('admin.layouts.template')

@section('title')
Sales Report
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>SALES REPORT - INVOICE WISE</h1>
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
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/memo-wise-date-to-date-sales-report') }}" id="date_form" method="post">
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
                            &nbsp<br>
                            <button  type="submit" class="btn btn-info btn-sm " style="">Submit</button>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                            <br>
                            @if(isset($start_date))
                            <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/report/memo-wise-date-to-date-sales-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                            @endif
                            </div>
                        </div>  
                    </form> 
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
                    <table class="table-bordered" width="100%" id="dataTable">
                            <thead>
                                <tr>
                                    <th height="25">Sl No.</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th style="text-align: center">Showing Price</th>
                                    <th style="text-align: center">Discount</th>
                                    <th style="text-align: center">Deposit</th>
                                    <th style="text-align: center">Due</th>
                                </tr>

                            </thead>

                            <tbody>
                                @php $net_sales=0; $net_discount=0; $net_paid=0; $net_due=0;
                                @endphp
                                @foreach($date_to_date_sales as $key => $data)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{$globalSettings->invoice_prefix."-BI-".str_pad($data->sales_master_id, 8, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{ $data->customer_name }} {{ $data->mobile_no }}</td>
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
                                    <th height="25" style="text-align: center">TOTAL</th>
                                    <th></th>
                                    <th></th>
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

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#dataTable').DataTable( {
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 
            

        ]
    } );
} );
</script>
@endsection