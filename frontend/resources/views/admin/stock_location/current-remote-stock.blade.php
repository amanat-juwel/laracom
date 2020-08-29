@extends('layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        STOCK LOOKUP
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Stock Lookup</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    List Items
                    <a class="btn btn-default btn-xs pull-right" href="{{ url('/stock_location/item/lookup/pdf') }}" target="">Download as PDF/Print</a>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%" height="25">Srl</th>
                                    <th style="width:30%">Item Name</th>
                                    <th style="width:30%">Item Code</th>
                                    <th style="width:15%">Catagory Name</th>
                                    <th style="width:10%">Stock</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($items))
                                    @foreach($items as $key => $data)
                                        @if($data->opening_stock_qty+$data->stock_in-$data->stock_out > 0) 
                                            <tr>
                                                <td height="25">{{ ++$key }}</td>
                                                <td>{{ $data->item_name }}</td>
                                                <td>{{ $data->item_code }}</td>
                                                <td>{{ $data->cata_name }}</td>
                                                <td class="@if($data->opening_stock_qty+$data->stock_in-$data->stock_out <= 0) btn-danger 
                                                    @elseif($data->opening_stock_qty+$data->stock_in-$data->stock_out > 0 && $data->opening_stock_qty+$data->stock_in-$data->stock_out <= 5 ) btn-warning @else btn-success @endif">{{ $data->opening_stock_qty+$data->stock_in-$data->stock_out  }}</td>

                                            </tr>
                                        @endif
                                    @endforeach
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