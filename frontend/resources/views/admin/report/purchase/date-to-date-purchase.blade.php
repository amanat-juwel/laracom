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
    PURCHASE REPORT
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Purchase Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/date-to-date-purchase-report') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">From</label>
                                <input autocomplete="OFF" type="text" id="datepicker" name="start_date" class="form-control input-sm" @if(isset($start_date)) value="{{ $start_date }}"@endif  required/>
                            </div>
                        </div>    
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label for="">To</label>
                                 <input autocomplete="OFF" type="text" id="datepicker2" name="end_date" class="form-control input-sm" @if(isset($end_date)) value="{{ $end_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
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
                            <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/report/date-to-date-purchase-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                            @endif
                            </div>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
        @if(isset($purchase_details))       
        <div class="col-md-12">
                <!--
                @if(isset($start_date))
                <p align="center"><b>From:</b> {{ $start_date }} <b>To:</b> {{ $end_date }}</p>
                @endif
                -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        List Purchase
                    </div>
                    <div class="panel-body">
                        
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Item</th>
                                    <th style="text-align: center;">Batch Code</th>
                                    <th style="text-align: center;">Particulars</th>
                                    <th style="text-align: center;">Quantity</th>
                                    <th style="text-align: center;"> Rate</th>
                                     <th style="text-align: center;"> Amount</th>
                                </tr>
                            </thead>

                            <tbody id="myTable">
                                @php $qty = 0; $total_purchase_rate = 0;$total_sales_rate = 0; @endphp

                                @foreach($purchase_details as $key => $data)
                                <tr>
                                    <td height="25">{{ ++$key }}</td>
                                    <td style="text-align: center;">{{ $data->purchase_date }} </td>
                                    <td style="text-align: center;">{{ $data->item_name }} </td>
                                    <td style="text-align: center;">{{ $data->code }} </td>
                                    <td style="text-align: center;">{{ $data->particulars }}</td>
                                    <td style="text-align: center;">{{ $data->stock_in }}</td>
                                    <td style="text-align: center;">{{ number_format($data->purchase_rate,2) }}</td>
                                    <td style="text-align: center;">{{ number_format($data->stock_in*$data->purchase_rate,2) }}</td>
                                  
                                </tr>
                                @php $qty += $data->stock_in; $total_purchase_rate += ($data->purchase_rate * $data->stock_in);
                                @endphp
                                @endforeach
                                <tr>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align: center;">{{$qty}}</th>
                                    <th></th>
                                    <th style="text-align: center;">{{number_format($total_purchase_rate,2)}}</th>
                                   
                                </tr>
                            </tbody>
                        </table>
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