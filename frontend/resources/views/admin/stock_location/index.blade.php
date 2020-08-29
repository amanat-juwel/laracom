@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        WAREHOUSE 
        
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Warehouse</li>
    </ol>
</section>
<!-- End Content Header -->

<!--  MODAL -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Warehouse</h4>
        </div>
        <div class="modal-body">
          <form class="form" action="{{ route('stock_location.store') }}" method="post" >
            {{ csrf_field() }}
            <div class="form-group">
                <label for="">Warehouse Name <span class="required">*</span></label>
                <input type="text" autocomplete="OFF" name="stock_location_name" placeholder="Warehouse Name" class="form-control  input-sm" required="" />
                @if($errors->has('stock_location_name'))
                    <span class="text-danger">{{ $errors->first('stock_location_name')}}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="">Code </label>
                <input type="text" autocomplete="OFF" name="code" placeholder="Code" class="form-control  input-sm" />
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code')}}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="">Address</label>
                <input type="text" autocomplete="OFF" name="address"  placeholder="Address" class="form-control  input-sm" />
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address')}}</span>
                @endif
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Save"/>
            </div>
        </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
      
    </div>
  </div>
<!-- //  MODAL -->

<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New Warehouse</button>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Srl</th>
                                    <th>Warehouse Name</th>
                                    <th>Code</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($stock_location))
                                    @foreach($stock_location as $key => $stock_locations)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $stock_locations->stock_location_name }}</td>
                                        <td>{{ $stock_locations->code }}</td>
                                        <td>{{ $stock_locations->address }}</td>
                                        <td>
                                            <div style="display:flex;">
                                                <a href="{{ url('/stock_location/'.$stock_locations->stock_location_id.'/edit') }}"><button class="edit btn btn-success btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                                &nbsp;&nbsp;&nbsp;
                                                @if($stock_locations->stock_location_id != 1)
                                                <form action="{{ url('/stock_location/'.$stock_locations->stock_location_id) }}" method="post">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button class="delete btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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