@extends('admin.layouts.template')
@section('template')
<style>
    td,th{
        padding: 2px;
    }
</style>
<section class="content-header">
    <h1>
         ACCOUNTS
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Account List</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Create New Account</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body">
                    <form class="form" action="{{ url('admin/bank/account/store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Account Name*</label>
                            <input type="text" autocomplete="OFF" name="bank_name" placeholder="Account Name" class="form-control input-sm" required/>
                        </div>

                        <div class="form-group">
                            <label for=""> Account Type</label>
                            <select name="account_group_id" id="account_group_id" class="form-control input-sm input-sm">
                                <option>--Select--</option>
                                @foreach($account_groups as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Account No</label>
                            <input type="text" autocomplete="OFF" name="bank_account" placeholder="Account No" class="form-control input-sm" />
                        </div>
                        <!-- <div class="form-group">
                            <label for="">Short Description</label>
                            <textarea rows="2" name="description" class="form-control input-sm"  placeholder="Note" ></textarea>
                        </div> -->
                        <div class="form-group">
                            <label for="">Opening Balance: Debit</label>
                            <input type="text" autocomplete="OFF" name="op_bal_dr" id="debit" placeholder="Debit Balance" class="form-control input-sm" />
                        </div>
                        <div class="form-group">
                            <label for="">Opening Balance: Credit</label>
                            <input type="text" autocomplete="OFF" name="op_bal_cr" id="credit" placeholder="Credit Balance" class="form-control input-sm" />
                        </div>
                        <div class="form-group hidden payment-method">
                            <label for="">Show this A/C in Payment method?</label><br>
                            <input type="checkbox" name="is_payment_method" value="1" > Yes
                        </div>
                         
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Create"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">List Accounts</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table-bordered" id="purchase_details" width="100%">
                                <thead>
                                    <tr>
                                        <th height="25">ID</th>
                                        <th style="text-align:left">Account</th>
                                        <th style="text-align:left">A/C No</th>
                                        <th style="text-align:left"> Type</th>
                                        <!-- <th style="text-align:left">Type</th> -->
                                         <th style="text-align:left"> Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($bank_accounts))
                                        @foreach($bank_accounts as $key => $bank_account)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $bank_account->bank_name }}</td>
                                            <td>{{ $bank_account->bank_account }}</td>
                                            <td>{{ $bank_account->group_name }}</td>
                                            <!-- <td>{{ $bank_account->acc_type_name }} | {{ $bank_account->sub_acc_type_name }}</td> -->
                                            @php $balance = $bank_account->op_bal_dr - $bank_account->op_bal_cr + $bank_account->debit - $bank_account->credit;  @endphp
                                            <td style="text-align: right;" >
                                                @if($bank_account->group_name!='Expense')
                                                    @if($balance>=0) {{ number_format($balance,2) }} @else {{ '('.number_format(abs($balance),2).')' }} @endif
                                                @endif
                                            </td>
                                            <td >
                                                <div style="display:flex;">
                                                    @if($bank_account->bank_account_id != 1 and $bank_account->bank_account_id != 2 )

                                                    <a href="{{ url('admin/bank/account/'.$bank_account->bank_account_id.'/edit') }}"><button class="edit btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    @if($bank_account->bank_account_id != 4)
                                                        <form action="{{ url('admin/bank/account/'.$bank_account->bank_account_id) }}" method="post">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button class="delete btn btn-danger btn-xs" onclick="return confirm('The Trasnsactions were made related to this Account will also be deleted. Do you want to proceed?');" title="Delete">
                                                            <i class="fa fa-trash-o" aria-hidden="true"> </i></button>
                                                        </form>

                                                    @endif
                                                    @endif
                                                </div>
                                            
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('update'))
                        <div class="alert alert-warning" id="update">
                            {{Session::get('update')}}
                            @php
                            Session::forget('update');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('delete'))
                        <div class="alert alert-danger" id="delete">
                            {{Session::get('delete')}}
                            @php
                            Session::forget('delete');
                            @endphp
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    
$(document).ready(function() {


    $('#account_group_id').on('change', function(){
        var account_group_id = parseInt($(this).val());

        if(account_group_id==1) {

           $('.payment-method').removeClass('hidden');
        }
        else{

           $('.payment-method').addClass('hidden');
        } 

    });

    $('#debit,#credit').on('keyup', function(){
        var debit = parseFloat($('#debit').val());
        var credit = parseFloat($('#credit').val());

        if(debit>0) {
           $('#credit').val(0);
        }
        else if(credit>0){
           $('#debit').val(0);
        } 

    });
});
</script>
@endsection