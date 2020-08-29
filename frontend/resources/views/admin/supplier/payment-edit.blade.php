@extends('admin.layouts.template')

@if(Auth::user()->role == 'admin')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Supplier Payment
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Supplier Payment</li>
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
                            <form class="form" action="{{ url('/supplier/transaction/'.$ledger->supplier_ledger_id) }}" method="post">
                                {{ method_field('PUT') }}
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
                                <label for="">Debit A/C</label>
                                <select name="supplier_id" id="supplier_id" class="form-control input-sm"  required > 
                                    <option value="">---Select---</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" @if($ledger->supplier_id == $supplier->supplier_id) selected @endif>{{ $supplier->sup_name }}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Credit A/C</label>
                                <select name="bank_account_id" class="form-control input-sm" required>
                                    <option value="">--- Select ---</option>
                                    @foreach($accounts as $key => $account)
                                    <option value="{{ $account->bank_account_id }}" @if($transaction->bank_account_id == $account->bank_account_id) selected @endif>{{ $account->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Amount*</label>
                                <input type="number" name="amount" class="form-control input-sm" autocomplete="OFF" placeholder="Enter Amount" required value="{{ $ledger->debit }}" />
                                @if($errors->has('amount'))
                                    <span class="text-danger">{{ $errors->first('amount')}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea rows="2" name="description" class="form-control input-sm"  placeholder="Description about transaction" >{{ $ledger->particulars }}</textarea>
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
        </section>
        <!-- End Main Content -->
    </div>
</div>
@endsection
@endif