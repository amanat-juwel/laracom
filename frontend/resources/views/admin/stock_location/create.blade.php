@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Add New Stock Location
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Add New Stock Location</li>
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
                            <form class="form" action="{{ route('stock_location.store') }}" method="post" >
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Stock Location Name</label>
                                    <input type="text" name="stock_location_name" placeholder="Stock Location Name" class="form-control" />
                                    @if($errors->has('stock_location_name'))
                                        <span class="text-danger">{{ $errors->first('stock_location_name')}}</span>
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