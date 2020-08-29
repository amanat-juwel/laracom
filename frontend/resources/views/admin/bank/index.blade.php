@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><b>+</b> Add New Transaction</button> -->
    <h1>TRANSACTION FLOW</h1>
    
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Transaction Flow</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                    <div class="alert alert-info" id="success">
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
                    <div class="panel panel-primary">
                      <div class="panel-heading"><a href="{{ url('admin/bank/transfer') }}" class="btn btn-default btn-sm" ><i class="fa fa-plus-circle"></i> Add New Transaction</a></div>
                      <div class="panel-body">
                        <table class="table-bordered" id="dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Srl.</th>
                                    <th>Date</th>
                                    <th style="text-align:center">Voucher No</th>
                                    <th style="text-align:center">Account</th>
                                    <th style="text-align:center">Description</th>
                                    <th style="text-align:center">Debit</th>
                                    <th style="text-align:center">Credit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($transactions))
                                    @foreach($transactions as $key => $transaction)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $transaction->transaction_date }}</td>
                                        <td>{{ $transaction->voucher_ref }}</td>
                                        <td>{{ $transaction->bank_name }}</td>
                                        <td>{{ $transaction->transaction_description }}</td>
                                        <td style="text-align:right">@php echo number_format($transaction->deposit,2) @endphp</td>
                                        <td style="text-align:right">@php echo number_format($transaction->expense,2) @endphp</td>
                                        <td style="text-align:center">
                                            
                                                
                                                <div style="display:flex;">
                                                  
                                                    <a href="{{ url('admin/bank/transaction/'.$transaction->bank_transaction_id.'/edit') }}"><button class="edit btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>
                                                    
                                                    <form action="{{ url('admin/bank/transaction/'.$transaction->bank_transaction_id) }}" method="post">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="voucher_ref" value="{{ $transaction->voucher_ref }}">
                                                        <button class="delete btn btn-danger btn-sm" onclick="return confirm('Deleting Transaction can not be undone. Current Balance with associated account will be adjusted.');" title="Delete">
                                                        <i class="fa fa-trash-o" aria-hidden="true"> </i></button>
                                                    </form>
                                                   
                                                </div>
                                                
                                           
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                      </div>
                    </div>

                <!-- START Modal -->
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Add Transaction</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form" action="{{ url('bank/transaction/store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Category*</label>
                                    <select class="form-control" name="category" required>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Expense">Withdraw</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Account*</label>
                                    <select class="form-control" name="bank_account_id" required>
                                        @foreach($bank_accounts as $bank_account)
                                            <option value="{{ $bank_account->bank_account_id }}">{{ $bank_account->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Amount*</label>
                                    <input autocomplete="off" type="number" name="amount" placeholder="Amount" class="form-control" required/>
                                </div>
                                <div class="form-group">
                                    <label for="">Details*</label>
                                    <textarea  rows="2" name="transaction_description" class="form-control" placeholder="Details about this transaction" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Date*</label>
                                    <input type="date" name="transaction_date" class="form-control" value="@php echo date('Y-m-d') @endphp" required/>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" value="Create"/>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                <!-- END Modal -->
                <div class="table-responsive">
                        
                        
                        
                    </div>
                </div>
     
<!--         <h5 class="text-success pull-right">Current Asset: {{ number_format($current_cash_in_hand) }} BDT</h5> -->
    </div>
</section>
<!-- End Main Content -->
@endsection