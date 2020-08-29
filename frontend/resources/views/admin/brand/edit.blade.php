@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Brand
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Brand</li>
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
                            <form class="form" action="{{ url('admin/brand/'.$brands->brand_id) }}" method="post" enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="">Brand Name</label>
                                    <input type="text" name="brand_name" placeholder="Brand Name" class="form-control" value="{{ $brands->brand_name }}" />
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
                                    <textarea rows="5" name="brand_details" class="form-control"  placeholder="Details about category" >{{ $brands->brand_details }}</textarea>
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
