@extends('admin.layouts.template')
@section('title')
  Order List
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>ORDER LIST</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Order List</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            List
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                    @endif
                    @if(Session::has('danger'))
                        <div class="alert alert-danger" id="success">
                            {{Session::get('danger')}}
                            @php
                            Session::forget('danger');
                            @endphp
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <th>DateTime</th>
                                    <th>Order ID.</th>
                                    <th>Customer</th>
                                    <th>Comments</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orders))
                                    @foreach($orders as $key => $data)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $data->datetime }}</td>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->customer_name }}</td>
                                            <td>{{ $data->comments }}</td>
                                            <td class="amount">{{ $data->total }}</td>
                                            <td>{{ $data->payment_method }}</td>
                                            @if($data->status == "Awaiting Payment")
                                            <td>
                                                {{ $data->status }}
                                                <br>
                                                <form action="{{ url('admin/orders/payment-received/'.$data->id) }}" method="post" style="display:inline-block">
                                                    {{ csrf_field() }}
                                                    <button title="Mark as Payment Received" class="btn btn-default btn-xs"  onclick="return confirm('Are you sure you want to proceed?');" >
                                                        <i class="fa fa-money" aria-hidden="true"></i> Payment Received
                                                    </button>
                                                </form>
                                                <br>
                                                <form action="{{ url('admin/orders/cancel/'.$data->id) }}" method="post" style="display:inline-block">
                                                    {{ csrf_field() }}
                                                    <button title="Cancel Order" class="delete btn btn-default btn-xs"  onclick="return confirm('Are you sure you want to cancle this order?');" >
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Cancel Order
                                                    </button>
                                                </form>
                                                <br>
                                                <form action="{{ url('admin/orders/delete/'.$data->id) }}" method="post" style="display:inline-block">
                                                    {{ method_field('DELETE') }} {{ csrf_field() }}
                                                    <button title="Delete Order" class="delete btn btn-default btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');" >
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete Order
                                                    </button>
                                                </form>
                                            </td>
                                            @elseif($data->status == "In-Process")
                                            <td>
                                                {{ $data->status }}
                                                <br>
                                                <form action="{{ url('admin/orders/delivered/'.$data->id) }}" method="post" style="display:inline-block">
                                                    {{ csrf_field() }}
                                                    <button title="Mark as Delivered" class=" btn btn-default btn-xs"  onclick="return confirm('Are you sure you want to proceed?');" >
                                                        <i class="fa fa-truck" aria-hidden="true"></i> Delivered
                                                    </button>
                                                </form>    
                                            </td>
                                            @elseif($data->status == "Delivered")
                                            <td>{{ $data->status }}</td>
                                            @else
                                            <td>{{ $data->status }}</td>
                                            @endif
                                            <td style="text-align: center">
                                                <a style="" title="View Details" target="_blank" class="btn btn-success btn-xs" href="{{ url('admin/orders/'.$data->id)  }}"><i class="fa fa-eye"></i> View</a>
                                                <!-- <a style="" title="Print Invoice" target="_blank" class="btn btn-primary btn-xs" href="{{ url('admin/orders/print/'.$data->id) }}"><i class="fa fa-print"></i> Print</a> -->
                                                
                                            </td>    
                                        </tr>
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

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#dataTable').DataTable( {
        paging: false,
        dom: 'Bfrtip',
        buttons: [

            {
              extend: 'copy',
              exportOptions: {
                   columns: [0,1,2,3,4,5,6]
               }
            },
            {
              extend: 'csv',
              exportOptions: {
                   columns: [0,1,2,3,4,5,6]
               }
            },
            {
              extend: 'excel',
              exportOptions: {
                   columns: [0,1,2,3,4,5,6]
               }
            },
            {
              extend: 'print',
              exportOptions: {
                   columns: [0,1,2,3,4,5,6]
               }
            }
            

        ]
    } );
} );
</script>
@endsection