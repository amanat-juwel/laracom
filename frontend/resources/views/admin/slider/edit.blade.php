@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>SLIDER - EDIT</h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('/sliders')}}">Blog</a></li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <form class="form" action="{{ url('admin/sliders/'.$slider->id) }}" method="post" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
        <div class="col-md-12">
            <section class="content">
                <div class="">
                    <div class="">
                        <div class="row">
                            <div class="col-md-12">
                              <div class="box box-primary box-solid">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Slider Info</h3>

                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                      <label  for="email">Type</label>
                                      <div >
                                        <select name="type" id="type" class="form-control input-sm" />
                                            <option value="main" @if($slider->type == 'main') selected @endif>Main</option>
                                            <option value="sidebar" @if($slider->type == 'sidebar') selected @endif>Sidebar</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input autocomplete="OFF" placeholder="" type="text" name="title" id="title" class="form-control input-sm" value="{{ $slider->title }}" required/>
                                        @if($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="">Sub Title</label>
                                        <textarea name="sub_title" class="form-control input-sm" rows="2" />{{ $slider->sub_title }}</textarea>
                                        @if($errors->has('sub_title'))
                                            <span class="text-danger">{{ $errors->first('sub_title')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Image</label>
                                        <input type="file" name="image" class="form-control input-sm" accept="image/*"/>
                                        @if($errors->has('image'))
                                            <span class="text-danger">{{ $errors->first('image')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Redirect To</label>
                                        <input type="text" autocomplete="OFF" name="redirect_link" id="redirect_link" placeholder="" value="{{ $slider->redirect_link }}" class="form-control input-sm" />
                                        @if($errors->has('redirect_link'))
                                            <span class="text-danger">{{ $errors->first('redirect_link')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Order</label>
                                        <input autocomplete="OFF" placeholder="" type="text" name="slider_order" id="slider_order" class="form-control input-sm" value="{{ $slider->slider_order }}" required/>
                                        @if($errors->has('slider_order'))
                                            <span class="text-danger">{{ $errors->first('slider_order')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="">Active</label>
                                        <input type="checkbox" name="active" value="1" class="" @if("$slider->active"=="1") checked @endif/>
                                        @if($errors->has('active'))
                                            <span class="text-danger">{{ $errors->first('active')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                    <br>
                                    <input type="submit" name="" value="Save" class="btn btn-success">
                                </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </form>      
</div>

@endsection

@section('script')
<script>
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

        $("#title").keyup(function() {
            var title = $("#title").val();
            $("#seo_title").val(title);
        });
    });
</script>
@endsection