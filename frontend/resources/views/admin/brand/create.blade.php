@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Create Brand
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Create Brand</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-6">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form" action="{{ url('admin/brand') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Brand Name*</label>
                                    <input type="text" name="brand_name" placeholder="example: Nokia" Title="Letters and Number only" pattern="[a-zA-Z0-9\s]+" class="form-control" />
                                    @if($errors->has('brand_name'))
                                        <span class="text-danger">{{ $errors->first('brand_name')}}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label for="">Brand Image</label>
                                        <input type="file" name="brand_image" placeholder="" class="form-control input-sm" />
                                        @if($errors->has('brand_image'))
                                            <span class="text-danger">{{ $errors->first('brand_image')}}</span>
                                        @endif
                                    </div> 
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
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>
</div>
@endsection