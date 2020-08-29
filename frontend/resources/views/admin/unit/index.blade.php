@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        UNIT
        
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="#"><i class="fa fa-dashboard"></i> Home</a>
        </li>
        <li class="active">Dashboard</li>
        <li class="active">Unit</li>
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
                        <i class="glyphicon glyphicon-plus"></i> Add New Unit
                    </button>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                      <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Srl</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($units)) @foreach($units as $key => $data)
                                <tr id="categoryList_{{ $data->id }}">
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <div style="display:flex;">
                                        <button class="edit btn btn-success btn-xs" data-id="{{ $data->id }}" data-target="#update_sub_category">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <form action="{{ url('admin/unit/'.$data->id) }}" method="post">
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
                                    <h4 class="modal-title">Add </h4>
                                </div>
                                <form class="form" action="{{url('admin/unit-insert')}}" id="insert_form" method="post">
                                <div class="modal-body">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="">Unit Name</label>
                                            <input type="text" autocomplete="OFF" name="name" id="name" placeholder="Name" class="form-control" required="" /> 
                                            @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name')}}</span>
                                            @endif
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
                <h4 class="modal-title">Update </h4>
            </div>
            <form class="form" action="{{url('admin/unit-update')}}" method="POST" id="update_form">
                <div class="modal-body">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="unit_id_1" >
                    <div class="form-group">
                        <label for="">Unit Name</label>
                        <input type="text" autocomplete="OFF" name="name" id="unit_name_1" placeholder=" Name" class="form-control"  />
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name')}}</span>
                        @endif
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
            url : '{{url("admin/unit-edit")}}',
            data : { 'id': id },
            success:function(data){
                $('#unit_id_1').val(data.id);
                $('#unit_name_1').val(data.name);
                $('#save').val('Update');
                $('#update_sub_category').modal('show');
            }
        });
    });

</script>

@endsection