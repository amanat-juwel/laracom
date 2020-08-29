@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1> TRANSACTION  <a href="{{ url('admin/bank/transaction/') }}" class="btn btn-info "><i class="fa fa-list"></i> LIST </a></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Transaction</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                      <div class="panel-heading">New Transaction</div>
                      <div class="panel-body">
                        <form class="form" id="myForm" action="{{ url('admin/bank/transfer/store') }}" method="post">
                            {{ csrf_field() }}<br>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><b>Date</b></label>
                                <div class="col-sm-9">
                                    <input autocomplete="OFF" type="text" id="datepicker" name="date" class="form-control input-sm" value="@php echo date('Y-m-d'); @endphp" required="" />
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><b class="text-green">Debit</b></label>
                                <div class="col-sm-9">
                                <select name="to_account" id="to_account" class="form-control input-sm select2" required>
                                    <option value="">--- Choose an Account ---</option>
                                    @foreach($accounts as $key => $account)
                                    <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                    @endforeach
                                </select>
                                 </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><b class="text-danger">Credit</b></label>
                                <div class="col-sm-9">
                                <select name="from_account" id="from_account" class="form-control input-sm select2" required>
                                    <option value="">--- Choose an Account ---</option>
                                    @foreach($accounts as $key => $account)
                                    <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                    @endforeach
                                </select>
                                 </div>
                            </div>
                            
                            <br><br>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><b>Amount</b></label>
                                <div class="col-sm-9">
                                <input autocomplete="OFF" type="text" name="amount" id="amount" class="form-control input-sm" placeholder="Ënter Amount" required/>
                                @if($errors->has('amount'))
                                    <span class="text-danger">{{ $errors->first('amount')}}</span>
                                @endif
                                 </div>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><b>Description</b></label>
                                <div class="col-sm-9">
                                <textarea rows="2" name="description" class="form-control input-sm"  placeholder="Description about head"></textarea>
                                 </div>
                            </div>
                            <br><br><br>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                  <input type="submit" class="btn btn-success" id="submit_form" value="Save"/>
                                </div>
                            </div>
                        </form>
                      </div>

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
</section>
<!-- End Main Content -->

<script type="text/javascript">
$(document).ready(function () {  

    $('#from_account').on('change', function(){
        if($("#from_account").val() == $("#to_account").val()){
            $("#from_account").val('')
            alert('Please choose a different account');
        }

        });

    $('#amount').on('change', function(){
        if($('#amount').val() < 0 || isNaN($('#amount').val())){
            alert("Wrong amount input");
            $('#amount').val('');
            $('#amount').focus();
        }
    });


    });

    
</script>

@endsection