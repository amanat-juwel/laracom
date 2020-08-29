@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>SLIDER - CREATE</h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('/Sliders')}}">Slider</a></li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <form class="form" action="{{ url('admin/sliders') }}" method="post" enctype="multipart/form-data">
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
                                        <label for="">Title</label>
                                        <input autocomplete="OFF" placeholder="" type="text" name="title" id="title" class="form-control input-sm" value="" />
                                        @if($errors->has('title'))
                                            <span class="text-danger">{{ $errors->first('title')}}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="">Sub Title</label>
                                        <textarea name="sub_title" class="form-control input-sm" rows="2" /></textarea>
                                        @if($errors->has('sub_title'))
                                            <span class="text-danger">{{ $errors->first('sub_title')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Image</label>
                                        <input type="file" name="image" class="form-control input-sm" accept="image/*" required/>
                                        @if($errors->has('image'))
                                            <span class="text-danger">{{ $errors->first('image')}}</span>
                                        @endif
                                    </div> 
                                    <div class="form-group">
                                        <label for="">Redirect To</label>
                                        <input type="text" autocomplete="OFF" name="redirect_link" id="redirect_link" placeholder="" value="" class="form-control input-sm" />
                                        @if($errors->has('redirect_link'))
                                            <span class="text-danger">{{ $errors->first('redirect_link')}}</span>
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
