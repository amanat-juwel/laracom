@extends('admin.layouts.template')
@section('title')
  VL-POS :: Sales Details
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        LIST SALES
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">List Sales</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{ url('admin/sales') }}" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i> Add New</a>
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
                                    <th>Invoice No.</th>
                                    <th>Customer Name</th>
                                    <th>Total</th>
                                    <th> Date</th>
                                    <!-- <th>Delivery Date</th> -->
                                    <!--<th>Payment</th>-->
                                    <!-- <th>Reference</th> -->
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($sales_detail))
                                    @foreach($sales_detail as $key => $sales_details)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <!-- <td><a class=" " href="{{ url('/sales/memo_details/'.$sales_details->sales_master_id) }}"> {{ $sales_details->voucher_ref }} </a></td> -->
                                            <td class="text-blue">{{ $globalSettings->invoice_prefix."-BI-".str_pad($sales_details->sales_master_id, 8, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $sales_details->customer_name }} {{ $sales_details->mobile_no }} {{ $globalSettings->invoice_prefix."-".str_pad($sales_details->customer_code, 8, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $sales_details->memo_total - $sales_details->discount }}</td>
                                            <td>{{ date('M-d-Y', strtotime($sales_details->sales_date)) }}</td>
                                            <!-- <td>{{ $sales_details->delivery_date }}</td> -->
                                            <!--@if($sales_details->memo_total-$sales_details->discount-$sales_details->advanced_amount > 0)-->
                                            <!--<td class="btn-danger">Due: {{ $sales_details->memo_total - $sales_details->advanced_amount - $sales_details->discount }}</td>-->
                                            <!--@else-->
                                            <!--<td class="btn-success">Paid</td>-->
                                            <!--@endif-->
                                            <!-- <td style="text-align: left;">@if(isset($sales_details->reference_by)){{ $sales_details->reference_by }}@endif</td> -->
                                            <td style="text-align: center">
                                                <a style="" title="View Sales Details" target="" class="btn btn-info btn-xs" href="{{ url('admin/sales/memo_details/'.$sales_details->sales_master_id)  }}"><i class="fa fa-eye"></i> View</a>
                                                <a style="" title="Print Invoice" target="_blank" class="btn btn-primary btn-xs" href="{{ url('admin/sales/invoice/'.$sales_details->sales_master_id) }}"><i class="fa fa-print"></i> Print</a>
                                                <!-- <a style="" title="Print Chalan" target="_blank" class="btn btn-default btn-xs" href="{{ url('/sales/chalan/'.$sales_details->sales_master_id) }}"><i class="fa fa-print"></i> Chalan</a> -->
                                                <!-- <a title="ReSend SMS" class="btn btn-warning btn-xs" href="{{ url('/sales/resend-sms/'.$sales_details->sales_master_id) }}"><i class="fa fa-comments-o"></i> ReSend SMS</a>
                                                <a title="Email Invoice" target="" class="btn btn-success btn-xs" href="{{ url('/sales/email-invoice/'.$sales_details->sales_master_id)  }}"><i class="fa fa-envelope"></i> Email Invoice</a>     -->    
                                                
                                                <form action="{{ url('admin/sales/'.$sales_details->sales_master_id) }}" method="post" style="display:inline-block">
                                                    {{ method_field('DELETE') }} {{ csrf_field() }}
                                                    <input type="hidden" name="voucher_ref" value="{{ $sales_details->voucher_ref }}">
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