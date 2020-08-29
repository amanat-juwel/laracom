@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        PURCHASE LIST 
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">List Purchases</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <a href="{{ url('admin/purchase') }}" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i> Add New</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                        <div class="alert alert-warning" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="30">Sl</th>
                                    <th>Bill No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Total</th>
									<th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($purchase_detail))
                                    @foreach($purchase_detail as $key => $purchase_details)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td><a class=" " href="{{ url('admin/purchase/memo_details/'.$purchase_details->purchase_master_id) }}">{{ $purchase_details->bill_no }}</a></td>
                                            <td>{{ $purchase_details->purchase_date }}</td>
                                            <td><a class="" href="{{ url('admin/supplier/'.$purchase_details->supplier_id) }}" target="_blank">{{ $purchase_details->sup_name }}</a></td>
                                            <td>{{ $purchase_details->memo_total }}</td>
                                           
                                            <td style="text-align: center"><a target=""  title="View" class="btn btn-info btn-xs" href="{{ url('admin/purchase/memo_details/'.$purchase_details->purchase_master_id) }}"><i class="fa fa-eye"></i> View</a>
                                                {{-- <a target="_blank" title="Print"  class="btn btn-primary btn-xs" href="{{ url('admin/purchase/invoice/'.$purchase_details->purchase_master_id) }}"><i class="fa fa-print"></i> Print</a> --}}
                                             
                                                <form action="{{ url('admin/purchase/'.$purchase_details->purchase_master_id) }}" method="post" style="display:inline-block">
                                                    {{ method_field('DELETE') }} {{ csrf_field() }}
                                                    <button title="Delete" class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');"  >
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