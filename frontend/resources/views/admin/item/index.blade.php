@extends('admin.layouts.template')

@section('title')
Item Info
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>ITEMS</h1>
     
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Item</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
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
                        <div class="panel panel-primary">
                          <div class="panel-heading">
                            <a href="{{ url('admin/item/create') }}" class="btn btn-default" ><i class="fa fa-plus"></i> Add New Item</a>
                          </div>
                          <div class="panel-body">
                              <div class="table-responsive">
                                <table class="table-bordered " id="dataTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:5%" height="25">Srl</th>
                                            <th style="width:20%">Item Name</th>
                                            <th style="width:25%">Info</th>
                                            <th style="width:12%">Image</th>
                                            <th style="width:10%">Prices</th>
                                            <th style="width:7%">Status </th>
                                            <th style="width:23%;text-align: center;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if(isset($items))
                                            @foreach($items as $key => $data)
                                            <tr>
                                                <td height="25">{{ ++$key }}</td>
                                                <td>{{ $data->item_name }}</td>
                                                <td>
                                                    <strong>Code:</strong> {{ str_pad($data->item_code, 4, '0', STR_PAD_LEFT) }}<br>
                                                    <strong>Catagory:</strong> {{ $data->cata_name }}<br>
                                                    <strong>Sub-Cat:</strong> {{ $data->sub_cata_name }}<br>
                                                    <strong>Brand:</strong> {{ $data->brand_name }}<br>
                                                    <strong>Unit:</strong> {{ $data->unit }}<br>
                                                    <strong>Re-Order Level(Min):</strong> {{ $data->reorder_level_min }}

                                                </td>
                                                <td>
                                                    @if(isset($data->item_image))
                                                    <img src="{{ asset($data->item_image) }}" height="50" width="50">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data->discounted_price!=null)
                                                    <del>{{ number_format($data->mrp,0) }}</del>
                                                    <span class="text-red">{{ number_format($data->discounted_price,0) }}</span> 
                                                    @else
                                                    {{ number_format($data->mrp,0) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data->is_active==1)
                                                    <span class="label label-success">Active</span>
                                                    @else
                                                    <span class="label label-danger">In-Active</span>
                                                    @endif
                                                </td>
                                                <td  style="text-align: center;">
                                                        <a class="btn btn-success btn-xs" href="{{ url('admin/item/'.$data->item_id.'/view') }}"><i class="fa fa-eye"></i> View</a>
                                                        
                                                        <a href="{{ url('admin/item/'.$data->item_id.'/edit') }}" class="  btn btn-info btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                                       
                                                        <form action="{{ url('admin/item/'.$data->item_id) }}" method="post" style="display:inline-block">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</button>
                                                        </form>
                                                    
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
                   columns: [0,1,2,4,5]
               }
            },
            {
              extend: 'csv',
              exportOptions: {
                   columns: [0,1,2,4,5]
               }
            },
            {
              extend: 'excel',
              exportOptions: {
                   columns: [0,1,2,4,5]
               }
            },
            {
              extend: 'print',
              exportOptions: {
                   columns: [0,1,2,4,5]
               }
            }
            

        ]
    } );
} );
</script>
@endsection