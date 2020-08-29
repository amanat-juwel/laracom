@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SUB-CATEGORY 
        
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="#"><i class="fa fa-dashboard"></i> Home</a>
        </li>
        <li class="active">Dashboard</li>
        <li class="active">Sub-Category </li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
           <div class="panel panel-primary">
              <div class="panel-heading">
                <button type="button" class="btn btn-default" data-toggle="modal" id="insert">
                        <i class="glyphicon glyphicon-plus"></i> Add New Sub-Sub-Category
                    </button>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                      <table class="table-bordered" id="dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">ID</th>
                                    <th>Sub-Sub-Catagory Name</th>
                                    <th>Sub-Catagory</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($sub_sub_categories)) @foreach($sub_sub_categories as $key => $sc)
                                <tr id="categoryList_{{ $sc->id }}">
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ $sc->name }}</td>
                                    <td>{{ $sc->sub_cata_name }}</td>
                                    <td>
                                        <div style="display:flex;">
                                        <button class="edit btn btn-success btn-xs" data-id="{{ $sc->id }}" data-target="#update_sub_category">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <form action="{{ url('admin/sub-sub-category/'.$sc->id) }}" method="post">
                                            {{ method_field('DELETE') }} {{ csrf_field() }}
                                            <button class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');"  >
                                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                  
                                    </td>
                                </tr>
                                @endforeach @endif
                            </tbody>
                        </table>
                    </div>
                    @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}} @php Session::forget('success'); @endphp
                    </div>
                    @endif @if(Session::has('update'))
                    <div class="alert alert-warning" id="update">
                        {{Session::get('update')}} @php Session::forget('update'); @endphp
                    </div>
                    @endif @if(Session::has('delete'))
                    <div class="alert alert-danger" id="delete">
                        {{Session::get('delete')}} @php Session::forget('delete'); @endphp
                    </div>
                    @endif
              </div>
            </div>
                    

                    <!-- INSERT MODAL -->
                    <div class="modal fade" id="insert_category" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title">Add Sub-Sub-Category</h4>
                                </div>
                                <form class="form" action="{{url('admin/sub-sub-category-insert')}}" id="insert_form" method="post">
                                <div class="modal-body">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="">Sub-Category Name</label>
                                            <input type="text" name="name" id="" placeholder="Sub-Category Name" class="form-control" required="" /> 
                                            @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name')}}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for=""> Under Category</label>
                                            <select name="sub_cata_id" class="form-control" required="">
                                                <option value="">--Select--</option>
                                                @foreach($sub_categories as $data)
                                                <option value="{{$data->id}}">{{$data->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                           
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success pull-left">Save</button>
                                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                                  
                                    </div>
                                    </form>
                            </div>
                        </div>
                    </div>  

                    
                </div>

            </div> 
      
<!-- START UPDATE MODAL -->
<div class="modal modal-default fade" id="update_sub_category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update Sub-Category</h4>
            </div>
            <form class="form" action="{{url('admin/sub-sub-category-update')}}" method="POST" id="update_form">
                <div class="modal-body">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="sub_sub_cata_id" >
                    <div class="form-group">
                        <label for="">Sub-Sub-Catagory Name</label>
                        <input type="text" name="name" id="name" placeholder="Catagory Name" class="form-control"  />
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name')}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for=""> Under Category</label>
                        <select name="sub_cata_id" id="sub_cata_id" class="form-control" required="">
                            <option value="">--Select--</option>
                            @foreach($sub_categories as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning pull-left">Update</button>
                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- // START UPDATE MODAL -->

</section>
<!-- End Main Content -->

<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $('#insert').on('click',function(){ $('#save').val('Save');
        $('#insert_form').trigger('reset'); 
        $('#insert_category').modal('show');
    });

    $('tbody').delegate('.edit', 'click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        //alert(id);
        $.ajax({
            type : 'get',
            url : '{{url("admin/sub-sub-category-edit")}}',
            data : { 'id': id },
            success:function(data){
                console.log(data)
                $('#sub_sub_cata_id').val(data.id);
                $('#name').val(data.name);
                $('#sub_cata_id').val(data.sub_cata_id);
                $('#save').val('Update');
                $('#update_sub_category').modal('show');
            }
        });
    });

</script>

@endsection