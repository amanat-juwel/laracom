@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Item Inventory History</li>
    </ol>
    <br>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="alert alert-info">
                                  <strong>Item: {{ $item_name }}</strong>
                                </div>
                                
                                <!-- <h4>Opening Stock Qty: <strong>{{ $opening_stock_qty }}</strong></h4> -->
                                <hr/>
                                <table class="table-bordered" id="purchase_details" width="100%">
                                    <thead>
                                        <tr>
                                            <th height="25">Srl</th>
                                            <th>Date</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($inventory_history_in))
                                            @php $total_stock_in = 0; $total_stock_out = 0; @endphp
                                            @foreach($inventory_history_in as $key => $single_data)
                                            <tr>
                                                <td height="25">{{ ++$key }}</td>
                                                <td>{{ $single_data->stock_change_date }}</td>
                                                <td>{{ $single_data->stock_in }}</td>
                                                <td>{{ $single_data->stock_out }}</td>
                                                <td>{{ number_format($single_data->purchase_price,2) }}</td>
                                            </tr>
                                            @php $total_stock_in += $single_data->stock_in; 
                                                 $total_stock_out += $single_data->stock_out; 
                                            @endphp
                                            @endforeach
                                            @foreach($inventory_history_out as $key => $single_data)
                                            <tr>
                                                <td height="25">{{ ++$key }}</td>
                                                <td>{{ $single_data->stock_change_date }}</td>
                                                <td>{{ $single_data->stock_in }}</td>
                                                <td>{{ $single_data->stock_out }}</td>
                                                <td>{{ number_format($single_data->sales_price,2) }}</td>
                                            </tr>
                                            @php $total_stock_in += $single_data->stock_in; 
                                                 $total_stock_out += $single_data->stock_out; 
                                            @endphp
                                            @endforeach
                                        @endif
                                    </tbody>
                                            <tr>
                                                <td height="25" colspan="2" style="text-align: center"><strong>Current Stock</strong></td>
                                                <td colspan="3"><strong>{{ $opening_stock_qty+$total_stock_in-$total_stock_out }}</strong></td>

                                            </tr>
                                </table>
                            </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection