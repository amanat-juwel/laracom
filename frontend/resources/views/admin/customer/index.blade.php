@extends('admin.layouts.template')

@section('title')
Customer Info
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        CUSTOMER
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Customer</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{ url('admin/customer/create') }}" class="btn btn-default btn-sm"><i class="fa fa-user-plus"></i> Add New</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                <div class="table-responsive">
                        @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('update'))
                        <div class="alert alert-warning" id="update">
                            {{Session::get('update')}}
                            @php
                            Session::forget('update');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('delete'))
                        <div class="alert alert-danger" id="delete">
                            {{Session::get('delete')}}
                            @php
                            Session::forget('delete');
                            @endphp
                        </div>
                        @endif

                        
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Cust ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Mobile</th>
                                    <th>Balance</th>
                                    <!--<th>Status</th>-->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if(isset($customer))
                                @php $total_balance = 0; @endphp
                                    @foreach($customer as $key => $customers)
                                    <tr>
                                        <td height="25">{{ $globalSettings->invoice_prefix."-".str_pad($customers->customer_code, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $customers->customer_name }}</td>
                                        <td>{{ $customers->category }}</td>
                                        <td>{{ $customers->mobile_no }}</td>
                                     
                                        @php
                                            $balance = $customers->credit + $customers->op_bal_credit - ($customers->debit + $customers->op_bal_debit);
                                            $total_balance += $balance;
                                        @endphp

                                        @if($balance == 0)
                                        <td class="btn btn-success btn-xs"> 0.00 </td>
                                        @elseif($balance < 0)
                                        <td class="btn btn-danger btn-xs">{{ number_format(abs($balance),2) }}</td>
                                        @else($balance > 0)
                                        <td class="btn btn-info btn-xs">{{ number_format($balance,2) }}</td>
                                        @endif

                                        <td>
                                            <div style="display:flex;">
                                               @if($customers->customer_name != 'Cash')
                                                <a href="{{ url('admin/customer/'.$customers->customer_id) }}"><button class="edit btn btn-info btn-xs" title="Show"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                                &nbsp;&nbsp;

                                                <a href="{{ url('admin/customer/'.$customers->customer_id.'/edit') }}"><button class="edit btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                                &nbsp;&nbsp;
                                                   
                                                    <form action="{{ url('admin/customer/'.$customers->customer_id) }}" method="post">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button class="delete btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                    </form>
                                                  
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                 
                                    <tr>
                                        <th>TOTAL</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                       
                                        @if($total_balance == 0)
                                        <th class="btn btn-success btn-xs"> 0.00 </th>
                                        @elseif($total_balance < 0)
                                        <th class="btn btn-danger btn-xs">{{ number_format(abs($total_balance),2) }}</th>
                                        @else($total_balance > 0)
                                        <th class="btn btn-info btn-xs">{{ number_format($total_balance,2) }}</th>
                                        @endif
                                        <th></th>
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
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
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
                   columns: [0,1,2,3,4,5]
               }
            },
            {
              extend: 'csv',
              exportOptions: {
                   columns: [0,1,2,3,4,5]
               }
            },
            {
              extend: 'excel',
              exportOptions: {
                   columns: [0,1,2,3,4,5]
               }
            },
            {
              extend: 'print',
              exportOptions: {
                   columns: [0,1,2,3,4,5]
               }
            }
            

        ]
    } );
} );
</script>
@endsection