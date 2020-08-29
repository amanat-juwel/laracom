@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>PAGE - CREATE</h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('admin/pages')}}">Page</a></li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <form class="form" action="{{ url('admin/pages') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
        <div class="col-md-6">
            <section class="content">
                <div class="">
                    <div class="">
                        <div class="row">
                            <div class="col-md-12">
                              <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Page Info </h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="">Page Title</label>
                                        <input autocomplete="OFF" placeholder="The tile for your Page" type="text" name="title" id="title" class="form-control input-sm" value="" required/>
                                        @if($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="">Excerpt</label>
                                        <input autocomplete="OFF" placeholder="Small description of this Page" type="text" name="excerpt" class="form-control input-sm" value=""/>
                                        @if($errors->has('excerpt'))
                                            <span class="text-danger">{{ $errors->first('excerpt')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <section class="custom-note">
                                            <div class="form-group">
                                                <label for=""></i>Body</label>
                                                <div class="input-group  input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <span class="ti-receipt"></span>
                                                        </div>
                                                    </div>
                                                    <textarea class="form-control" rows="10" id="summernote" name="body"></textarea>
                                                </div>
                                            </div>
                                        </section> 
                                       <!--  <label for="">Page Content</label>
                                        <textarea name="body" class="form-control input-sm" value="" rows="15" /></textarea>
                                        @if($errors->has('body'))
                                            <span class="text-danger">{{ $errors->first('body')}}</span>
                                        @endif -->
                                    </div>
                                </div>
                              </div>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-md-6">
            <section class="content">
                <div class="">
                    <div class="">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="box box-warning box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Page Details</h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="box-body">
                                  <div class="form-group">
                                        <label for="">URL Slug</label>
                                        <input type="text" autocomplete="OFF" name="slug" id="slug" placeholder="" value="" class="form-control input-sm" required/>
                                        @if($errors->has('slug'))
                                            <span class="text-danger">{{ $errors->first('slug')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Page Status </label>
                                        <select name="status" id="" class="form-control input-sm"> 
                                            <option value="PUBLISHED">PUBLISHED</option>
                                            <option value="DRAFT">DRAFT</option>
                                        </select>
                                        @if($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status')}}</span>
                                        @endif
                                    </div>

                                </div>
                              </div>
                            </div>

                            <!-- <div class="col-md-12">
                              <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Page Media</h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="">Image</label>
                                        <input type="file" name="image" class="form-control input-sm" accept="image/*"/>
                                        @if($errors->has('image'))
                                            <span class="text-danger">{{ $errors->first('image')}}</span>
                                        @endif
                                    </div> 
                                </div>
                              </div>
                            </div> -->

                            <div class="col-md-12">
                              <div class="box box-info box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title">SEO Content</h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="box-body">
                                  <div class="form-group">
                                        <label for="">Meta Description</label>
                                        <textarea name="meta_description" class="form-control input-sm" value="" rows="2" /></textarea>
                                        @if($errors->has('meta_description'))
                                            <span class="text-danger">{{ $errors->first('meta_description')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Meta Keywords</label>
                                        <input type="text" autocomplete="OFF" name="meta_keywords" placeholder="" value="" class="form-control input-sm" />
                                        @if($errors->has('meta_keywords'))
                                            <span class="text-danger">{{ $errors->first('meta_keywords')}}</span>
                                        @endif
                                    </div>

                                </div>
                              </div>
                              <div class="form-group">
                                    <br>
                                    <input type="submit" name="" value="Save" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </form>    
</div>

<!-- modal for image -->
 <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
         <form action="{{url('admin/page/image-upload')}}" method="POST" role="form" id="createForm" enctype="multipart/formData">
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

@endsection

@section('script')
<script>

    /*START SUMMARNOTE*/
    $(document).ready(function() {

         $('#summernote').summernote({height:300,});

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
                  url: "{{url('admin/page/image-upload')}}",
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

    $('document').ready(function () {
        function string_to_slug(str, separator) {
            str = str.trim();
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            const from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
            const to = "aaaaaaeeeeiiiioooouuuunc------";

            for (let i = 0, l = from.length; i < l; i++) {
                str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
            }

            return str
                .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
                .replace(/\s+/g, "-") // collapse whitespace and replace by -
                .replace(/-+/g, "-") // collapse dashes
                .replace(/^-+/, "") // trim - from start of text
                .replace(/-+$/, "") // trim - from end of text
                .replace(/-/g, separator);
        }

        $("#title").keyup(function() {
            var slug = string_to_slug($("#title").val(),'-');
            $("#slug").val(slug);
        });

    });
</script>
@endsection