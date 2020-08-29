@extends('layouts.template')

@section('title')
  VL-POS :: Sales Details
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        LIST SALES RETURN
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">List Sales Return</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{ url('/sales-return') }}" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i> Add New</a>
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
                                    <th>Date</th>
                                    <th>Voucher Ref</th>
                                    <th>Customer Name</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                    <!-- <th>Reference</th> -->
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($all_sales_return))
                                    @foreach($all_sales_return as $key => $value)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $value->date }}</td>
                                            <td>{{ $value->voucher_ref }}</td>
                                            <td>{{ $value->customer_name }}</td>
                                            <td>{{ $value->memo_total }}</td>
                                            <td>{{ $value->advanced_amount }}</td>
                                            @if($value->status == "Due")
                                            <td class="btn-danger">{{ $value->status }}:{{ $value->memo_total - $value->advanced_amount -$value->discount }}</td>
                                            @else
                                            <td class="btn-success">{{ $value->status }}</td>
                                            @endif
                                            <!-- <td style="text-align: left;">@if(isset($value->reference_by)){{ $value->reference_by }}@endif</td> -->
                                            <td style="text-align: center">
                                                <a style="" title="View Details" target="" class="btn btn-info btn-xs" href="{{ url('/sales-return/details-view/'.$value->sales_return_master_id)  }}"><i class="fa fa-eye"></i> View</a>
                                            
                                                @if(Auth::user()->role == 'admin')
                                                <form action="{{ url('/sales-return/'.$value->sales_return_master_id) }}" method="post" style="display:inline-block">
                                                    {{ method_field('DELETE') }} {{ csrf_field() }}
                                                    <input type="hidden" name="voucher_ref" value="{{ $value->voucher_ref }}">
                                                    <button title="Delete" class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');" >
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                    </button>
                                                </form>
                                                @endif
                                                
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