@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Add New Executive
        <small></small>
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Add New</li>
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
                            <form class="form" action="{{ url('human-resource/store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-text-width
                                            "></i></span>
                                        <input type="text" name="name" placeholder="Enter Name" class="form-control" />
                                    </div>
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Designation</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-get-pocket
                                            "></i></span>
                                        <input type="text" name="designation" placeholder="Enter Designation" class="form-control" />
                                    </div>
                                    @if($errors->has('designation'))
                                        <span class="text-danger">{{ $errors->first('designation')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope
                                            "></i></span>
                                        <input type="email" name="email" placeholder="Enter Email" class="form-control" />
                                    </div>
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-phone-square
                                            "></i></span>
                                        <input type="text" name="phone" placeholder="Enter Phone" class="form-control" />
                                    </div>
                                    @if($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-map-marker
                                            "></i></span>
                                        <input type="text" name="address" placeholder="Enter Address" class="form-control" />
                                    </div>
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">NID/Identification No</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-crosshairs  
                                            "></i></span>
                                        <input type="text" name="nid" placeholder="Enter NID" maxlength="17" class="form-control" />
                                    </div>
                                    @if($errors->has('nid'))
                                        <span class="text-danger">{{ $errors->first('nid')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Joining Date</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar
                                            "></i></span>
                                        <input type="text" id="datepicker" name="joining_date" value="{{date('Y-m-d')}}" class="form-control" />
                                    </div>
                                    @if($errors->has('designation'))
                                        <span class="text-danger">{{ $errors->first('designation')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Salary</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money
                                            "></i></span>
                                        <input type="text" name="salary" placeholder="Enter Salary" class="form-control" />
                                    </div>
                                    @if($errors->has('salary'))
                                        <span class="text-danger">{{ $errors->first('salary')}}</span>
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
    </div>

</div>
@endsection