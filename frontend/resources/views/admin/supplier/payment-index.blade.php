@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SUPPLIER 
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Supplier List</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
        
            <a href="{{ url('admin/supplier') }}" class="btn btn-default btn-sm pull-right"><i class="fa fa-list"></i> Supplier List </a>

            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-money"></i> Add New</button>
        </div>
        <!-- //Transaction MODAL -->  
            <div id="myModal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Payment to Supplier</h4>
                  </div>
                  <div class="modal-body">
                    <form class="form" action="{{ url('admin/supplier/transaction') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Date*</label>
                            <input type="text" name="date" id="datepicker" class="form-control input-sm" value="@php echo date('Y-m-d'); @endphp"/>
                        </div>
                        <input type="hidden" name="type" value="Pay" />
                        <!-- <div class="form-group">
                            <label for="">Type*</label>
                            <select name="type" class="form-control input-sm" required>
                                <option value="Pay">Pay Money</option>
                                <option value="Discount">Discount</option>

                            </select>
                        </div> -->
                        <div class="form-group">
                            <label for="" class="text-green">Supplier Account *</label>
                            <select name="supplier_id" id="supplier_id" class="form-control select2"  required style="width: 100%;"> 
                                <option value="">---Select---</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}">{{ $supplier->sup_name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="text-danger">Payment method *</label>
                            <select name="bank_account_id" class="form-control select2" required style="width: 100%;">
                                <option value="">--- Select ---</option>
                                @foreach($accounts as $key => $account)
                                <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Amount*</label>
                            <input type="number" name="amount" class="form-control input-sm" autocomplete="OFF" placeholder="Enter Amount" required/>
                            @if($errors->has('amount'))
                                <span class="text-danger">{{ $errors->first('amount')}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea rows="2" name="description" class="form-control input-sm"  placeholder="Description about transaction" ></textarea>
                        </div>                        
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Save"/>
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                  </div>

                </div>

              </div>
            </div> 
            <!-- // Transaction MODAL -->
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Srl</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($supplier_payments))
                                    @foreach($supplier_payments as $key => $data)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $data->transaction_date }}</td>
                                        <td>{{ $data->sup_name }}</td>
                                        <td>{{ $data->particulars }}</td>
                                        <td>{{ $data->debit }}</td>
                                        <td>{{ $data->bank_name }}</td>
                                        
                                        <td>
                                            <div style="display:flex;">
                                                
                                              <!--   <a href="{{ url('/supplier/payment/'.$data->supplier_ledger_id) }}"><button class="edit btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </button> </a>
                                           
                                                &nbsp;&nbsp;
                                                 -->
                                                <form action="{{ url('admin/supplier/payment/'.$data->supplier_ledger_id) }}" method="post">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button class="delete btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                </form>
                                             
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
<!-- End Main Content -->
@endsection

@section('script')
<style>
    .select2-container{
        z-index: 99999;
    }
</style>
@endsection