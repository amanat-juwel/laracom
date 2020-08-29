@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        EDIT WAREHOUSE 
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Warehouse </li>
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
                            <form class="form" action="{{ url('/stock_location/'.$stock_locations->stock_location_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="">WAREHOUSE Name</label>
                                    <input type="text" name="stock_location_name" placeholder="Warehouse Name" class="form-control  input-sm" value="{{ $stock_locations->stock_location_name }}" required="" />
                                    @if($errors->has('stock_location_name'))
                                        <span class="text-danger">{{ $errors->first('stock_location_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Code</label>
                                    <input type="text" autocomplete="OFF" name="code" value="{{ $stock_locations->code }}" class="form-control input-sm" />
                                    @if($errors->has('code'))
                                        <span class="text-danger">{{ $errors->first('code')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <input type="text" autocomplete="OFF" name="address" value="{{ $stock_locations->address }}" class="form-control  input-sm" />
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" value="Update"/>
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