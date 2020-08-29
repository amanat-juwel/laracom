@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        LIST MONEY RECEIPT
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">List Money-Receipt</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{ url('admin/money-receipt/create') }}" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i> Add New</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                    @endif
                    @if(Session::has('danger'))
                        <div class="alert alert-danger" id="success">
                            {{Session::get('danger')}}
                            @php
                            Session::forget('danger');
                            @endphp
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <!-- <th>Invoice No.</th> -->
                                    <th>Date</th>
                                    <th>M.R No.</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <!-- <th>Customer</th> -->
                                 <!--    <th>Narration</th> -->
                                    <th>Payment Method</th>
                                    
                                    
                                    <!-- <th>Reference</th> -->
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($money_receipts))
                                    @foreach($money_receipts as $key => $data)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $data->date }}</td>
                                            <td class="text-blue">{{ $globalSettings->invoice_prefix."-MR-".str_pad($data->mr_id, 8, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $data->payment_by }}</td>
                                            <td>{{ $data->amount }}</td>
                                            <!-- <td>{{ $data->customer_name }} {{ $globalSettings->invoice_prefix."-".str_pad($data->customer_code, 8, '0', STR_PAD_LEFT) }}</td> -->
                                         <!--    <td>{{ $data->narration }}</td> -->
                                            <td>{{ $data->bank_name }}</td>
                                            
                                            
        
                                                <td style="text-align: center">
                                                <a style="" title="Print M.R" target="_blank" class="btn btn-success btn-xs" href="{{ url('admin/money-receipt/print/'.$data->mr_id) }}"><i class="fa fa-print"></i> Print</a>
                                                
                                                <a style="" title="" target="" class="btn btn-primary btn-xs" href="{{ url('admin/money-receipt/edit/'.$data->mr_id) }}"><i class="fa fa-edit"></i> Edit</a>
                                                
                                                <!-- <a style="" title="" target="" class="btn btn-warning btn-xs" href="{{ url('/money-receipt/'.$data->mr_id.'/edit') }}"><i class="fa fa-edit"></i> Edit</a> -->
                                                
                                            
                                                <form action="{{ url('admin/money-receipt/'.$data->mr_id) }}" method="post" style="display:inline-block">
                                                    {{ method_field('DELETE') }} {{ csrf_field() }}
                                                    <input type="hidden" name="voucher_ref" value="{{ $data->voucher_ref }}">
                                                    <button title="Delete" class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');" >
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
                                          
                                                
                                            </td>    
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection