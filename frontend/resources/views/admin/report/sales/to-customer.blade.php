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
    <h1>
    CUSTOMER SALES REPORT
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Customer Sales Report</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">

                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/date-to-date-sales/to-customer') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Customer</label>
                                <select name="customer_id" class="form-control select2" required>
                                    <option value="">--- Choose---</option>
                                    @foreach($customers as $data)
                                    <option value="{{ $data->customer_id }}" @if(isset($customer_id)) @if($customer_id==$data->customer_id) selected @endif @endif >{{ $data->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
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
                            <br>
                            <input type="submit" class="btn btn-sm btn-success" value="Submit">
                         <!--    <div class="form-group">
                            <br>
                            @if(isset($start_date))
                            <a class="btn btn-warning btn-xs pull-right" href="{{ url('/report/date-to-date-purchase-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                            @endif
                            </div> -->
                        </div>  
                    </form>
                </div>
            </div>
        </div>
        @if(isset($all_sales))       
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    List Sales
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <!-- <th>Supplier</th> -->
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <!-- <th>Vat %</th> -->
                                    <th>Total</th>
                                    <th>Invoice No.</th>
                                    <!-- <th>Paid</th>
                                    <th>Status</th> -->
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($all_sales))
                                @php $qty = 0; $total =0 ; $temp_date = ''; @endphp
                                    @foreach($all_sales as $key => $single_sales)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                         
                                            <td>
                                                @if($temp_date != date('d M, Y',strtotime($single_sales->sales_date)))
                                                    {{ date('d M, Y',strtotime($single_sales->sales_date)) }}
                                                @endif
                                                @php $temp_date = date('d M, Y',strtotime($single_sales->sales_date)); @endphp
                                            </td>                                 
                                            <td>{{ $single_sales->item_name }}</td>
                                            <td>{{ $single_sales->quantity }}</td>
                                            <td class="amount">{{ $single_sales->sales_price }}</td>
                                           
                                            <td class="amount">{{ $net = ($single_sales->sales_price *  $single_sales->quantity) + ($single_sales->item_vat/100) * ($single_sales->sales_price *  $single_sales->quantity) }}</td>

                                            <td>{{ $single_sales->sales_master_id }}</td>

                                        </tr>
                                        @php
                                            $qty += $single_sales->quantity; 
                                            $total += $single_sales->sales_price *  $single_sales->quantity;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                     
                                        <td></td>                                 
                                        <td>TOTAL</td>
                                        <td>{{ $qty }}</td>
                                        <td></td>
                                       
                                        <td class="amount">{{ number_format($total,2) }}</td>

                                        <td></td>

                                    </tr>
                                @endif
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
            'copy', 'csv', 'excel', 'print'
            

        ]
    } );
} );
</script>
@endsection