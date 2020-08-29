@extends('admin.layouts.template')

@section('title')
Current Stock
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        STOCK REPORT
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Stock Report</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    LIST
                   <!--  <a class="btn btn-success btn-xs" href="{{ url('/purchase') }}" target="">Add New</a> -->
                    <a class="btn btn-default btn-xs pull-right" target="_blank" href="{{ url('admin/report/finished-product/current-stock-print') }}" target="">Download as PDF/Print</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%" height="25">Srl</th>
                                    <th style="width:35%">Item Name</th>
                                    <th style="width:23%">Catagory Name</th>
                                    <th style="width:22%">Brand Name</th>
                                    <th style="width:15%">Stock</th>
                             
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($item))
                                @php
                                    $qty = 0;
                                @endphp
                                    @foreach($item as $key => $items)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $items->item_name }}</td>
                                        <td>{{ $items->cata_name }}</td>
                                        <td>{{ $items->brand_name }}</td>
                                        <td class="qty-center">{{ $items->stock_in-$items->stock_out  }}</td>
                                        
                                    </tr>
                                    @php
                                        $qty += ($items->stock_in-$items->stock_out);
                                    @endphp
                                    @endforeach
                                    <tr>
                                        <th height="25">{{ ++$key }}</th>
                                        <th>TOTAL</th>
                                        <th></th>
                                        <th></th>
                                        <th class="qty-center"> {{ $qty }}</th>
                                        
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
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