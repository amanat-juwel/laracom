@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Account
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Account</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-6">
        <section class="content">
            
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                <div class="panel-body">
                            <form class="form" name="editForm" action="{{ url('admin/bank/account/'.$singleAccountById->bank_account_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                <label for="">Bank Name*</label>
                                <input type="text" name="bank_name" value="{{ $singleAccountById->bank_name }}"  class="form-control input-sm" required/>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="">Account Type</label>
                                    <select name="account_type_id" id="" class="form-control input-sm" > 
                                        <option value="">---Select---</option>
                                        <option value="1">Asset</option>
                                        <option value="2">Liability</option>
                                        <option value="3">Equity</option>
                                    </select>
                                    @if($errors->has('account_type_id'))
                                        <span class="text-danger">{{ $errors->first('account_type_id')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Sub-Account Type</label>
                                    <select name="sub_account_type_id" class="form-control input-sm input-sm select2">
                                        <option>--Select--</option>
                                        @foreach($sub_acc_type as $data)
                                        <option value="{{$data->id}}" @if($data->id == $singleAccountById->sub_account_type_id) selected @endif>{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="form-group">
                                    <label for="">Account No</label>
                                    <input type="text" name="bank_account" value="{{ $singleAccountById->bank_account }}" class="form-control input-sm" />
                                </div>
                                
                                <div class="form-group">
                                    <label for="">Account Type</label>
                                    <select name="account_group_id" class="form-control input-sm input-sm">
                                        <!-- <option>--Select--</option> -->
                                        @foreach($account_groups as $data)
                                        <option value="{{$data->id}}">{{$data->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                
                                <!-- <div class="form-group">
                                    <label for="">Short Description</label>
                                    <textarea rows="2" name="description" class="form-control input-sm">{{ $singleAccountById->description }}</textarea>
                                </div> -->
                                <div class="form-group">
                                    <label for="">Opening Balance: Debit</label>
                                    <input type="text" name="op_bal_dr" value="{{ $singleAccountById->op_bal_dr }}" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('op_bal_dr'))
                                        <span class="text-danger">{{ $errors->first('op_bal_dr')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Opening Balance: Credit</label>
                                    <input type="text" name="op_bal_cr" value="{{ $singleAccountById->op_bal_cr }}" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('op_bal_cr'))
                                        <span class="text-danger">{{ $errors->first('op_bal_cr')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Show in Payment method?</label><br>
                                    <input type="checkbox" name="is_payment_method" value="1" @if($singleAccountById->is_payment_method == 1) checked @endif> Yes
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-warning" value="Update"/>
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
<script type="text/javascript">

    document.forms['editForm'].elements['account_group_id'].value="{{$singleAccountById->account_group_id}}";
</script>
@endsection
