@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Category
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Category</li>
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
                            <form class="form" action="{{ url('/category/'.$categories->cata_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="">Catagory Name</label>
                                    <input type="text" name="cata_name" placeholder="Catagory Name" class="form-control" value="{{ $categories->cata_name }}" />
                                    @if($errors->has('cata_name'))
                                        <span class="text-danger">{{ $errors->first('cata_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Category Details</label>
                                    <textarea rows="5" name="cata_details" class="form-control"  placeholder="Details about category" >{{ $categories->cata_details }}</textarea>
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
