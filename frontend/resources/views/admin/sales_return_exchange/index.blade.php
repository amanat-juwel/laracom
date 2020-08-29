@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>LIST SALES RETURN & EXCHANGE</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Sales Return & Exchange</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{ url('admin/sales-return/exchange/create') }}" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i> Add New</a>
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
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <th>Date</th>
                                    <th>Sale Invoice#</th>
                                    <th>Purchase Invoice#</th>
                                    <th>User</th>
                                   <!--  <th style="text-align: center;">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($sales_return_exchanges))
                                    @foreach($sales_return_exchanges as $key => $data)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
                                            <td>{{ $data->sales_master_id }}</td>
                                            <td>{{ $data->purchase_master_id }}</td>
                                            <td style="text-align: center">@if(isset($data->name)){{ $data->name }}@endif</td>
                         <!--                    <td style="text-align: center">
                                                <div style="display:flex;">
                                                    <a style="" title="View Sales Details" target="" class="btn btn-info btn-xs" href="{{ url('/sales-return/exchange/'.$data->id)  }}"><i class="fa fa-eye"></i> View</a> &nbsp
                                                    <a style="" title="" target="_blank" class="btn btn-primary btn-xs" href="{{ url('/sales-return/exchange/'.$data->id.'/print')  }}"><i class="fa fa-print"></i> Print</a>&nbsp

                                                    @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                                                        <form action="{{ url('/sales-return/exchange/'.$data->id) }}" method="post">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                    </form>
                                                </div>
                                                @endif
                                                
                                            </td>  -->   
                                        </tr>
                                    @endforeach
                                @endif
                                <!-- DELETE MODAL -->
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">DELETE INVOICE</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-red">This activity will be added in system log history. Are you sure you want to delete this invoice?</p>
                                                  <form action="{{ url('admin/sales/delete/invoice') }}" method="post" style="display:inline-block">
                                                        {{ method_field('DELETE') }} {{ csrf_field() }}
                                                        
                                                        <label>Reason to delete</label>
                                                        <textarea rows="2" class="form-control input-sm" name="reason" required=""></textarea>
                                                        <input type="hidden" name="sales_master_id" id="sales_master_id">
                                                        <br>
                                                        <input type="submit" class="btn btn-success btn-sm" value="Submit">                                                    
                                                    </form> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                      </div>
                                      
                                    </div>
                                  </div>
                                  <!-- DELETE MODAL -->
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


    $('tbody').delegate('.inv_delete', 'click', function (e) {
        e.preventDefault();
        var sales_master_id = $(this).data('id');
        $('#sales_master_id').val(sales_master_id);

    });

</script>

@endsection