@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        CATEGORY 
        
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="#"><i class="fa fa-dashboard"></i> Home</a>
        </li>
        <li class="active">Dashboard</li>
        <li class="active">Category </li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
              <div class="panel-heading">
                    <a data-toggle="modal" class="btn btn-default btn-sm" data-target="#myModal"><i class="fa fa-plus"></i> Add New</a>
                    <!--Category ADD-->
                    <div id="myModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-black">ADD</h4>
                          </div>
                          <div class="modal-body">
                            <form class="form" action="{{ url('admin/category') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Category Name</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-text-width
                                            "></i></span>
                                        <input type="text" name="cata_name" placeholder="Enter Category Name" class="form-control" autocomplete="OFF" />
                                    </div>
                                    @if($errors->has('cata_name'))
                                        <span class="text-danger">{{ $errors->first('cata_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Category Details</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-text-width
                                            "></i></span>
                                        <input type="text" name="cata_details" placeholder="Enter Category Details" class="form-control" autocomplete="OFF" />
                                    </div>
                                    @if($errors->has('cata_details'))
                                        <span class="text-danger">{{ $errors->first('cata_details')}}</span>
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
                    <!-- // Catgeory ADD-->

                </div>
              <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table-bordered" id="dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">ID</th>
                                    <th>Catagory Name</th>
                                    <th>Catagory Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($category)) @foreach($category as $key => $categories)
                                <tr id="categoryList_{{ $categories->cata_id }}">
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ $categories->cata_name }}</td>
                                    <td>{{ $categories->cata_details }}</td>
                                    <td>
                                        <div style="display:flex;">
                                        <button class="edit btn btn-success btn-xs" data-id="{{ $categories->cata_id }}"   data-target="#update_category">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <form action="{{ url('admin/category/'.$categories->cata_id) }}" method="post">
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
        </div>
        <!-- </div>
        </div> -->
    </div>
@include('admin.category.update')
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


    $('#insert_form').on('submit',function(e){
        e.preventDefault();
        var insertForm = $('#insert_form');
        var formData = insertForm.serialize();
        var url=insertForm.attr('action');
        $.ajax({
            type:'POST',
            url:url,
            data:formData,
            success:function(data)
            {
                window.location.reload();
                // console.log(data);
                // $('#insert_form').trigger('reset');
                // $('#cata_name').focus();
                // var table = $('#category').DataTable();
                alert('Successfully Inserted');
                // $('#cata_name,#cata_details').val('');
                // var totalRow = table.rows().count();
                // totalRow++;
                // var row = '<tr>'+
                //             '<td>'+totalRow+'</td>'+
                //             '<td>'+data.cata_name+'</td>'+
                //             '<td>'+data.cata_details+'</td>'+
                //             '<td><div style="display:flex;"><button class="edit" data-id="'+data.id+'" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> &nbsp;&nbsp;||&nbsp;&nbsp;'+
                //             '<button class="delete" data-id="'+data.id+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div></td>'+
                //             '</tr>';
                //     $('tbody').append(row);
            },
            error: function (data) 
            {

            }
        });
    });

    $('tbody').delegate('.edit', 'click', function (e) {
        e.preventDefault();
        var cata_id = $(this).data('id');
        //alert(cata_id);
        $.ajax({
            type : 'get',
            url : 'category_edit',
            data : { 'id': cata_id },
            success:function(data){
                $('#cata_id_1').val(data.cata_id);
                $('#cata_name_1').val(data.cata_name);
                $('#cata_details_1').val(data.cata_details);
                $('#save').val('Update');
                $('#update_category').modal('show');
            }
        });
    });
    // $('tbody').delegate('.delete', 'click', function () {
    //     var cata_id = $(this).data('id');
        
    //     if(confirm('Are You Sure To Delete?') == true){
    //         $.ajax({
    //             type : 'post',
    //             url : 'category_delete',
    //             data : { 'id': cata_id },
    //             success:function(data){
    //                 console.log(data);
    //                 // alert(data.sms);
    //                 // $('#insert_form'+cata_id).remove();
    //             }
    //         });
    //     }

    // });

    $('#update_form').on('submit',function(e){
        e.preventDefault();
        var update_form = $('#update_form');
        var formData = update_form.serialize();
        var url = update_form.attr('action');
        $.ajax({
            type : 'POST',
            url : url,
            data : formData,
            success : function(data)
            {
                // console.log(data);
                // $('#update_form').trigger('reset');
                // $('#cata_name').focus();
                window.location.reload();
                    
                    //var tr = $('#categoryList_'+data.cata_id).find('td:eq(0)').text();
                    alert('Successfully Updated');
                    // var row = '<tr id="#categoryList_"'+data.cata_id+'>'+
                    //             '<td>'+tr+'</td>'+
                    //             '<td>'+data.cata_name+'</td>'+
                    //             '<td>'+data.cata_details+'</td>'+
                    //             '<td><div style="display:flex;"><button class="edit" data-id="'+data.cata_id+'" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> &nbsp;&nbsp;||&nbsp;&nbsp;'+
                    //             '<button class="delete" data-id="'+data.cata_id+'"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div></td>'+
                    //             '</tr>';
                    // $('#categoryList_'+data.cata_id).replaceWith(row);
                
            },
            error: function (data) {
                //$('#orderItemError').text('At least 1 order item required.');
                
            }
        });
    });


</script>
@endsection