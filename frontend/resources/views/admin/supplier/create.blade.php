@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Add New Supplier
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Add New Supplier</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-12">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form class="form" name="myForm" id="myForm" action="{{ url('admin/supplier') }}" method="post" enctype="multipart/form-data">
                               {{ csrf_field() }}
                               <h4 class="text text-info"><u><b>Personal Info</b></u></h4>
                                <div class="form-group">
                                    <label for="">Supplier Name*</label>
                                    <input type="text" autocomplete="OFF" name="sup_name" placeholder="Supplier Name" class="form-control input-sm" required/>
                                    @if($errors->has('sup_name'))
                                        <span class="text-danger">{{ $errors->first('sup_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier Address</label>
                                    <textarea rows="1" name="sup_address" class="form-control input-sm"  placeholder="Supplier Address" ></textarea>
                                    @if($errors->has('sup_address'))
                                        <span class="text-danger">{{ $errors->first('sup_address')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier Phone Number</label>
                                    <input type="text" name="sup_phone_no" placeholder="Supplier Phone Number" class="form-control input-sm" />
                                    @if($errors->has('sup_phone_no'))
                                        <span class="text-danger">{{ $errors->first('sup_phone_no')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier Email Address</label>
                                    <input type="text" name="sup_email" placeholder="Supplier Email Address" class="form-control input-sm" />
                                    @if($errors->has('sup_email'))
                                        <span class="text-danger">{{ $errors->first('sup_email')}}</span>
                                    @endif
                                </div>    
                                 <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea rows="1" name="note" class="form-control input-sm"></textarea>
                                </div> 
                              
                               
                           
                        </div>

                        <div class="col-md-4">
                                <h4 class="text text-info"><u><b>Opening Balance Info</b></u></h4>

                                <div class="form-group">
                                    <label class="text-green">Debit Balance [Advanced to Supplier]</label>
                                    <input type="number" name="debit" placeholder="" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('debit'))
                                        <span class="text-danger">{{ $errors->first('debit')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="text-red">Credit Balance [Payable to Supplier]</label>
                                    <input type="number" name="credit" placeholder="" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('credit'))
                                        <span class="text-danger">{{ $errors->first('credit')}}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="submit" class="btn btn-success pull-right" value="Save"/>
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