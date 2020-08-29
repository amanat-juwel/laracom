@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Customer Details
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Customer Details</li>
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
                            <form class="form" action="{{ url('admin/customer/'.$customers->customer_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="">Customer Name <span class="required">*</span></label></label>
                                    <input type="text" name="customer_name" placeholder="Customer Name" class="form-control input-sm" value="{{ $customers->customer_name }}" required />
                                    @if($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name')}}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="">Mobile No</label>
                                    <input type="text" name="mobile_no" placeholder="Mobile No" class="form-control input-sm" value="{{ $customers->mobile_no }}" />
                                    @if($errors->has('mobile_no'))
                                        <span class="text-danger">{{ $errors->first('mobile_no')}}</span>
                                    @endif
                                </div>
                               
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" name="email" placeholder="Email" class="form-control input-sm" value="{{ $customers->email }}" />
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email')}}</span>
                                    @endif
                                </div>
                                <!-- <div class="form-group">
                                    <label for="">Customer NID</label>
                                    <input type="text" name="customer_nid" placeholder="Customer NID" class="form-control input-sm" value="{{ $customers->customer_nid }}" maxlength="17" pattern="[0-9]+" title="17 digit NID Number"/>
                                    @if($errors->has('customer_nid'))
                                        <span class="text-danger">{{ $errors->first('customer_nid')}}</span>
                                    @endif
                                </div> -->
                                
                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea rows="1" name="note" class="form-control input-sm">{{ $customers->note }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Category <span class="required">*</span></label><br>
                                    <select name="category" id="cust_category" class="form-control select2" required=""> 
                                        <option value=''>---Select---</option>
                                        @foreach($customer_categories as $data)
                                            <option value="{{ $data->id }}">{{$data->cat_name}} </option>
                                        @endforeach
                                    </select>
                                </div> 
                                
                        </div>
                        <div class="col-md-4">
                                
                                <div class="form-group">
                                    <label class="text-green">Debit Balance [Receivable from Customer]</label>
                                    <input type="text" name="debit" value="{{ $customers->op_bal_debit }}" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('op_bal_debit'))
                                        <span class="text-danger">{{ $errors->first('op_bal_debit')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="text-red">Credit Balance [Advanced from Customer]</label>
                                    <input type="text" name="credit" value="{{ $customers->op_bal_credit }}" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('op_bal_debit'))
                                        <span class="text-danger">{{ $errors->first('op_bal_debit')}}</span>
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
<script>
$(document).ready(function () {
   $("#cust_category").val({{ $customers->category }});
});
</script> 
@endsection
