@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Supplier Details
       
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Supplier Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-8">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form" action="{{ url('admin/supplier/'.$suppliers->supplier_id) }}" method="post" enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                <label for="">Supplier Name*</label>
                                <input type="text" name="sup_name" placeholder="Supplier Name" pattern="[a-zA-Z\s]+" value="{{ $suppliers->sup_name }}" class="form-control" required/>
                                @if($errors->has('sup_name'))
                                    <span class="text-danger">{{ $errors->first('sup_name')}}</span>
                                @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier Address</label>
                                    <textarea rows="1" name="sup_address" class="form-control"  placeholder="Supplier Address" >{{$suppliers->sup_address}}</textarea>
                                    @if($errors->has('sup_address'))
                                        <span class="text-danger">{{ $errors->first('sup_address')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier Phone Number</label>
                                    <input type="text" name="sup_phone_no" value="{{ $suppliers->sup_phone_no }}" placeholder="Supplier Phone Number" class="form-control" />
                                    @if($errors->has('sup_phone_no'))
                                        <span class="text-danger">{{ $errors->first('sup_phone_no')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier Email Address</label>
                                    <input type="text" name="sup_email" value="{{ $suppliers->sup_email }}" placeholder="Supplier Email Address" class="form-control" />
                                    @if($errors->has('sup_email'))
                                        <span class="text-danger">{{ $errors->first('sup_email')}}</span>
                                    @endif
                                </div>    

                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea rows="1" name="note" class="form-control">{{ $suppliers->note }}</textarea>
                                </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Change Supplier Photo</label>
                                    <input type="file" name="sup_photo_new" placeholder="" class="form-control" />
                                    @if($errors->has('sup_photo'))
                                        <span class="text-danger">{{ $errors->first('sup_photo')}}</span>
                                    @endif
                                    <input type="hidden" name="sup_photo_old" value="{{ $suppliers->sup_photo}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-green">Debit Balance [Advanced to Supplier]</label>
                                    <input type="number" name="debit" value="{{ $suppliers->op_bal_debit }}" autocomplete="OFF" class="form-control" />
                                    @if($errors->has('debit'))
                                        <span class="text-danger">{{ $errors->first('debit')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="text-red">Credit Balance [Payable to Supplier]</label>
                                    <input type="number" name="credit" value="{{ $suppliers->op_bal_credit }}" autocomplete="OFF" class="form-control" />
                                    @if($errors->has('credit'))
                                        <span class="text-danger">{{ $errors->first('credit')}}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="">Status</label>
                                    <div class="form-group">
                                        <select class="form-control  input-sm" id="is_active" name="is_active">
                                              <option value="">--SELECT--</option>
                                              <option value="1">Active</option>
                                              <option value="0">Inactive</option>
                                          </select>
                                    </div> 
                                    @if($errors->has('unit'))
                                        <span class="text-danger">{{ $errors->first('unit')}}</span>
                                    @endif
                                </div> 
                                <div class="form-group">
                                    <input type="submit" class="btn btn-warning pull-right" value="Update"/>
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
<script>
    $(document).ready(function(){
        $('#is_active').val({{$suppliers->is_active}}); 
    });
</script>
@endsection