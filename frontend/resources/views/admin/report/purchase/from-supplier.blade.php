@extends('admin.layouts.template')

@section('title')
Purchase Report
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
    SUPPLIER PURCHASE REPORT
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Supplier Purchase Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">

                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/date-to-date-purchase/from-supplier') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Supplier</label>
                                <select name="supplier_id" class="form-control select2" required>
                                    <option value="">--- Choose---</option>
                                    @foreach($suppliers as $data)
                                    <option value="{{ $data->supplier_id }}" @if(isset($supplier_id)) @if($supplier_id==$data->supplier_id) selected @endif @endif >{{ $data->sup_name }}</option>
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
        @if(isset($all_purchases))       
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    List Purchase
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Date</th>
                                    <th>Invoice No.</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                 
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($all_purchases))
                                @php $qty = 0; $total =0 ; $temp_date = ''; @endphp
                                    @foreach($all_purchases as $key => $single_purchase)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>
                                                @if($temp_date != date('d M, Y',strtotime($single_purchase->purchase_date)))
                                                    {{ date('d M, Y',strtotime($single_purchase->purchase_date)) }}
                                                @endif
                                                @php $temp_date = date('d M, Y',strtotime($single_purchase->purchase_date)); @endphp
                                            </td> 
                                            <td><a href="{{ url('/purchase/memo_details/'.$single_purchase->purchase_master_id) }}">{{ $single_purchase->purchase_master_id }}</a></td>
                                
                                            <td>{{ $single_purchase->item_name }}</td>
                                            <td class="qty-center">{{ $single_purchase->stock_in }}</td>
                                            <td class="amount">{{ $single_purchase->purchase_rate }}</td>
                                       
                                            <td class="amount">{{ $net = $single_purchase->purchase_rate *  $single_purchase->stock_in }} </td>

                                            
                                        </tr>
                                        @php
                                            $qty += $single_purchase->stock_in; 
                                            $total += $single_purchase->purchase_rate *  $single_purchase->stock_in;
                                        @endphp
                                    @endforeach
                                        <tr>
                                            <th>TOTAL</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="qty-center">{{$qty}}</th>
                                            <th></th>
                                            <th class="amount">{{number_format($total,2)}}</th>
                                        </tr>
                                @endif
                            </tbody>
                        </table>
                        <h4 class="text-red">Total Quantity: {{ $qty }}</h4>
                        <h4 class="text-red">Total Amount: {{ number_format($total,2) }}</h4>
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