@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Create Category
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Create Category</li>
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
                            <form class="form" action="{{ route('category.store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Category Name</label>
                                    <input type="text" name="cata_name" placeholder="Category Name" required="" pattern="[a-zA-Z0-9\s]+" class="form-control" />
                                    @if($errors->has('cata_name'))
                                        <span class="text-danger">{{ $errors->first('cata_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Category Details</label>
                                    <textarea rows="5" name="cata_details" class="form-control"  placeholder="Details about category" ></textarea>
                                    @if($errors->has('cata_details'))
                                        <span class="text-danger">{{ $errors->first('cata_details')}}</span>
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