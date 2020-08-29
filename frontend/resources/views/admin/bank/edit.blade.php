@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Transaction
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Transaction</li>
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
                            <form class="form" name="myForm" action="{{ url('admin/bank/transaction/'.$singleTransactionById->bank_transaction_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"><b>Voucher Ref</b></label>
                                    <div class="col-sm-9">
                                        <input type="text" id="" name="voucher_ref" class="form-control input-sm" value="{{$singleTransactionById->voucher_ref}}" readonly="" />
                                    </div>
                                </div>
                                <br><br>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"><b>Date</b></label>
                                    <div class="col-sm-9">
                                        <input type="text" id="datepicker" name="date" class="form-control input-sm" value="{{$singleTransactionById->transaction_date}}" required="" />
                                    </div>
                                </div>
                                @foreach($transactions as $data)
                                @if($data->deposit > 0)
                                <input type="hidden" name="bank_transaction_id_to" value="{{$data->bank_transaction_id}}" />
                                <br><br>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"><b>Debit</b></label>
                                    <div class="col-sm-9">
                                    <select name="to_account" id="to_account" class="form-control input-sm" required>
                                        <option value="">--- Choose an Account ---</option>
                                        @foreach($accounts as $key => $account)
                                        <option value="{{ $account->bank_account_id }}" @if($account->bank_account_id == $data->bank_account_id) selected @endif>{{ $account->bank_name }}</option>
                                        @endforeach
                                    </select>
                                     </div>
                                </div>
                                @endif
                                @if($data->expense > 0)
                                <input type="hidden" name="bank_transaction_id_from" value="{{$data->bank_transaction_id}}" />
                                <br><br>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"><b>Credit</b></label>
                                    <div class="col-sm-9">
                                    <select name="from_account" id="from_account" class="form-control input-sm" required>
                                        <option value="">--- Choose an Account ---</option>
                                        @foreach($accounts as $key => $account)
                                        <option value="{{ $account->bank_account_id }}" @if($account->bank_account_id == $data->bank_account_id) selected @endif>{{ $account->bank_name }}</option>
                                        @endforeach
                                    </select>
                                     </div>
                                </div>
                                @endif
                                @endforeach
                                                                
                                <br><br>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"><b>Amount</b></label>
                                    <div class="col-sm-9">
                                    <input type="text" name="amount" id="amount" class="form-control input-sm" value="{{ $singleTransactionById->deposit }}" required/>
                                    @if($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount')}}</span>
                                    @endif
                                     </div>
                                </div>
                                <br><br>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"><b>Description</b></label>
                                    <div class="col-sm-9">
                                    <textarea rows="2" name="description" class="form-control input-sm"  placeholder="Description about head" required>{{ $singleTransactionById->transaction_description }}</textarea>
                                     </div>
                                </div>
                                <br><br><br>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                      <input type="submit" class="btn btn-success" id="submit_form" value="Update"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>

<!-- <script type="text/javascript">
    document.forms['myForm'].elements['to_account'].value="{{ $singleTransactionById->bank_account_id }}";
    document.forms['myForm'].elements['from_account'].value="{{ $singleTransactionById->bank_account_id }}";
</script> -->

</div>
@endsection

