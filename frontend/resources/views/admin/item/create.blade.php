@extends('admin.layouts.template')

@section('template')

<style type="text/css">
    label{
        font-weight: bold;
    }
</style>

<!-- Content Header -->
<section class="content-header">
    <h1>
        ITEM
        <small></small>
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Add New Item</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-6">
        <section class="content">
            <div class="panel panel-primary">
                <div class="panel-heading">
                   Info
                </div>
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/item/store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-12"> 
                            <div class="form-group">
                                <label for="">Item Name *</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i style="color:black" class="fa fa-cube
                                    "></i></span>
                                    <input autocomplete="off" type="text" name="item_name" id="item_name"  placeholder="Item Name" class="form-control input-sm" required />
                                </div>
                                @if($errors->has('item_name'))
                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            <div class="form-group">
                                <label for="">Item Code </label>
                                <div class="input-group">
                                <span class="input-group-addon"><i style="color:black" class="fa fa-tags
                                    "></i></span>
                                    <input autocomplete="off" type="text" name="item_code" id="item_code"  placeholder="Item Code" class="form-control input-sm" />
                                </div>
                                @if($errors->has('item_code'))
                                    <span class="text-danger">{{ $errors->first('item_code')}}</span>
                                @endif
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Category *</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i style="color:black" class="fa fa-bars
                                    "></i></span>
                                    <select name="cata_id" id="cata_id" class="form-control select2"  required> 
                                        <option value="">---Select---</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->cata_id }}">{{ $category->cata_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">    
                            <div class="form-group">
                                <label for="">Sub-Category</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i style="color:black" class="fa fa-tasks
                                    "></i></span>
                                    <select name="sub_cata_id" id="sub_cata_id" class="form-control select2"  > 
                                        <option value="">---Select---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">    
                            <div class="form-group">
                                <label for="">Sub-Sub-Category</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i style="color:black" class="fa fa-tasks
                                    "></i></span>
                                    <select name="sub_sub_cata_id" id="sub_sub_cata_id" class="form-control select2"  >
                                        @foreach($sub_sub_categories as $sub_sub_cat) 
                                            <option value="">---Select---</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">  
                            <div class="form-group">
                                <label for="">Brand Name *</label>
                                <div class="input-group">
                                <span class="input-group-addon"><i style="color:black" class="fa fa-shirtsinbulk
                                    "></i></span>
                                    <select name="brand_id" id="brand_id" class="form-control select2"  required> 
                                        <!-- <option value="">---Select---</option> -->
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>   
                        <div class="col-md-6">  
                            <div class="form-group">
                                <label for="">Item Image</label>
                                <input type="file" name="item_image" placeholder="" class="form-control input-sm" />
                                @if($errors->has('item_image'))
                                    <span class="text-danger">{{ $errors->first('item_image')}}</span>
                                @endif
                            </div> 
                        </div> 
                        
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">Unit</label>
                                <select class="form-control select2" id="unit_id" name="unit_id" required="" required="">
                                      
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                  </select>
                                @if($errors->has('unit'))
                                    <span class="text-danger">{{ $errors->first('unit')}}</span>
                                @endif
                            </div> 
                        </div>
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">Sales Rate </label>
                                <input type="text" autocomplete="OFF" name="mrp"  placeholder="" class="form-control input-sm"  />@if($errors->has('mrp'))
                                <span class="text-danger">{{ $errors->first('mrp')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">Discounted Price </label>
                                <input type="text" autocomplete="OFF" name="discounted_price"  placeholder="" class="form-control input-sm"  />@if($errors->has('discounted_price'))
                                <span class="text-danger">{{ $errors->first('discounted_price')}}</span>
                                @endif
                            </div>
                        </div>
                       
                        <div class="col-md-6">  
                            <div class="form-group">
                                <label for="">Re-Order Level (Min)</label>
                                <input type="number" value="5" autocomplete="OFF" name="reorder_level_min" autocomplete="OFF"  class="form-control input-sm" />
                                @if($errors->has('reorder_level_min'))
                                    <span class="text-danger">{{ $errors->first('reorder_level_min')}}</span>
                                @endif
                            </div> 
                        </div>

                    
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <section class="content">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Opening Balance
                </div>
                    <div class="panel-body">
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">Date of Creation: </label>
                                <input type="text" autocomplete="OFF" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="date" id="date" class=" form-control input-sm" required/>
                            </div>
                        </div>
                        
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">Opening Qty <span style="color:blue;">(If item already in stock)</span></label>
                                <input type="text" autocomplete="OFF" name="opening_qty"  placeholder="Qty" class="form-control input-sm"  /> 
                                @if($errors->has('opening_qty'))
                                <span class="text-danger">{{ $errors->first('opening_qty')}}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-12">  
                            <div class="form-group">
                                <label for="">Purchase Rate </label>
                                <input type="text" autocomplete="OFF" name="purchase_rate"  placeholder="" class="form-control input-sm"  /> 
                                @if($errors->has('purchase_rate'))
                                <span class="text-danger">{{ $errors->first('purchase_rate')}}</span>
                                @endif
                            </div>
                        </div>

                </div>
            </div>
        </section>
    </div>

    <div class="col-md-12">
        <section class="content">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Description & Specification
                </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <section class="custom-note">
                                    <div class="form-group">
                                        <label for=""></i>Description</label>
                                        <div class="input-group mb-3 input-group-sm">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <span class="ti-receipt"></span>
                                                </div>
                                            </div>
                                            <textarea class="form-control" id="summernote" name="description"></textarea>
                                        </div>
                                    </div>
                                </section>  
                        </div>
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for=""></i>Specification</label>
                                    <div class="input-group mb-3 input-group-sm">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <span class="ti-receipt"></span>
                                            </div>
                                        </div>
                                        <textarea class="form-control" id="summernote-2" name="specification"></textarea>
                                    </div>
                                </div>
                        </div>
                        

                        <div class="col-md-12"> 
                            <div class="form-group">
                                <button type="submit" name="action" value="save" class="btn btn-success">Save</button>
                                <button type="submit" name="action" value="save_and_close" class="btn btn-warning">Save &amp; Close</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-6">
        <br>
        @if(Session::has('success'))
        <div class="alert alert-success" id="success">
            {{Session::get('success')}}
            @php
            Session::forget('success');
            @endphp
        </div>
        @endif
    </div>
</div>


<!-- modal for image -->
 <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
         <form action="{{url('admin/item/image-upload')}}" method="POST" role="form" id="createForm" enctype="multipart/formData">
         {{ csrf_field() }}
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Image Upload</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <input type="file" name="custom_image" id="custom_image">
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createBtn">Save changes</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
<!-- modal for image -->

<script type="text/javascript">

/*START SUMMARNOTE*/
$(document).ready(function() {

     $('#summernote').summernote({width: 1000});
     $('#summernote-2').summernote({width: 1000});

     //start code for custom note
     var append = '<div class="note-btn-group btn-group note-view"><button type="button" id="custom-img" data-toggle="modal" data-target="#myModal"><i class="note-icon-picture"></i></button></div>' ;
     $('.custom-note').find('.note-btn-group').last().append(append);
     $('#custom-img').click(function() {
      
    });

      //SETUP AJAX TOKEN
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });


      var createForm = $("#createForm");
      var modal  = $(".modal");
      var image = $('#custom_image')[0].files[0];
      var result = $("#result");
      createForm.submit(function(event) {

          event.preventDefault();

          $.ajax({
              url: "{{url('admin/item/image-upload')}}",
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              success: function(data) {
                  var custom_image ='<img src="{{asset('')}}'+data.image_url+'"/>';
                  //$('#editorImg').append(custom_image) ;
                  $('.note-editable').first().append(custom_image);
                  //$('#summernote').summernote('code',custom_image);
                  modal.modal('hide') ;
              }

        });

    })
});
/*END SUMMARNOTE*/
    
$(document).ready(function() {
    
    $('#brand_id').val(1)
    $("#loader").hide(); 

    $('#cata_id').on('change', function(){

        //Set product name
        // var cat_name = $("#cata_id option:selected").text();
        // var item_code = $('#item_code').val();
        // $('#item_name').val(cat_name+'-'+item_code);
        //Set product name
        var cata_id = $(this).val();
        if(cata_id) {
            $.ajax({
                url:"{{url('admin/sub-category/get/')}}"+"/"+cata_id,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $("#loader").show();
                },
                success:function(data) {
                    $('#sub_cata_id').empty();
                    $('#sub_cata_id').append('<option>---Select---</option>');
                    $.each(data, function(key, value){
                        $('#sub_cata_id').append('<option value="'+ data[key]['id'] +'">' + data[key]['name'] + '</option>');
                    });
                },
                complete: function(){
                    $("#loader").hide();
                },
                error: function (data) {
                    $('#Error').text('Error occured.');
                }
            });
        } else {
            $('#sub_cata_id').empty();
        }

    });

    $('#sub_cata_id').on('change', function(){

        //Set product name
        // var cat_name = $("#cata_id option:selected").text();
        // var item_code = $('#item_code').val();
        // $('#item_name').val(cat_name+'-'+item_code);
        //Set product name
        var sub_cata_id = $(this).val();
        if(sub_cata_id) {
            $.ajax({
                url:"{{url('admin/sub-sub-category/get/')}}"+"/"+sub_cata_id,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $("#loader").show();
                },
                success:function(data) {
                    $('#sub_sub_cata_id').empty();
                    $('#sub_sub_cata_id').append('<option>---Select---</option>');
                    $.each(data, function(key, value){
                        $('#sub_sub_cata_id').append('<option value="'+ data[key]['id'] +'">' + data[key]['name'] + '</option>');
                    });
                },
                complete: function(){
                    $("#loader").hide();
                },
                error: function (data) {
                    $('#Error').text('Error occured.');
                }
            });
        } else {
            $('#sub_sub_cata_id').empty();
        }

    });

});
</script>

@endsection