@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>BRAND</h1>

    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Brand</li>
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
          <h4 class="modal-title">Add New </h4>
        </div>
        <div class="modal-body">
          <form class="form" action="{{ url('admin/brand') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="">Brand Name*</label>
                    <input type="text" name="brand_name" placeholder="example: Nokia" Title="Letters and Number only" pattern="[a-zA-Z0-9\s]+" class="form-control" />
                    @if($errors->has('brand_name'))
                        <span class="text-danger">{{ $errors->first('brand_name')}}</span>
                    @endif
                </div>
             
                <div class="form-group">
                    <label for="">Brand Image</label>
                    <input type="file" name="brand_image" placeholder="" class="form-control input-sm" />
                    @if($errors->has('brand_image'))
                        <span class="text-danger">{{ $errors->first('brand_image')}}</span>
                    @endif
                </div> 
                                
                <div class="form-group">
                    <label for="">Brand Details</label>
                    <textarea rows="5" name="brand_details" class="form-control"  placeholder="Details about brand (Optional)" ></textarea>
                    @if($errors->has('brand_details'))
                        <span class="text-danger">{{ $errors->first('brand_details')}}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Save"/>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
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
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New Brand</button>
                      </div>
                      <div class="panel-body">
                          <div class="table-responsive">
                            <table class="table-bordered" id="dataTables" width="100%">
                                <thead>
                                    <tr>
                                        <th height="25">ID</th>
                                        <th>Brand Name</th>
                                        <th>Image</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($brand))
                                        @foreach($brand as $key => $brands)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $brands->brand_name }}</td>
                                            <td>
                                                @if(isset($brands->brand_image))
                                                    <img src="{{ asset($brands->brand_image) }}" >
                                                @endif
                                            </td>
                                            <td>{{ $brands->brand_details }}</td>
                                            <td>
                                                <div style="display:flex;">
                                                    <a href="{{ url('admin/brand/'.$brands->brand_id.'/edit') }}"><button class="edit btn btn-success btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <form action="{{ url('admin/brand/'.$brands->brand_id) }}" method="post">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button class="delete btn btn-danger  btn-xs" onclick="return confirm('Are you sure you want to delete this item?');">
                                                        <i class="fa fa-trash-o" aria-hidden="true"> Delete</i></button>
                                                    </form>
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