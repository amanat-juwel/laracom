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
                                    <th>Invoice No</th>
                                    <!-- <th>Bill No</th> -->
                                    <th>Customer </th>
                                    <th>Total</th>
                                    <th>Sales Date</th>
                                    <!-- <th>Status</th> -->
                                    <th>User</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($sales_detail))
                                    @foreach($sales_detail as $key => $sales_details)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td><a class=" " href="{{ url('/sales/memo_details/'.$sales_details->sales_master_id) }}"> inv{{ $sales_details->sales_master_id }} </a></td>
                                            <!-- <td>{{ $sales_details->memo_no }}</td> -->
                                            <td>{{ $sales_details->customer_name }}</td>
                                            <td>{{ $sales_details->memo_total }}</td>
                                            <td>{{ date('d-M-Y', strtotime($sales_details->sales_date)) }}</td>
                                           <!--  @if($sales_details->status == "Due")
                                            <td class="btn-danger">{{ $sales_details->status }}:{{ $sales_details->memo_total - $sales_details->advanced_amount -$sales_details->discount }}</td>
                                            @else
                                            <td class="btn-success">{{ $sales_details->status }}</td>
                                            @endif -->
                                            <td style="text-align: center">@if(isset($sales_details->sold_by)){{ $sales_details->sold_by }}@endif</td>
                                            <td style="text-align: center">
                                                <a style="" title="View Sales Details" target="" class="btn btn-info btn-xs" href="{{ url('admin/sales/memo_details/'.$sales_details->sales_master_id)  }}"><i class="fa fa-eye"></i> View</a>
                                                <a style="" title="Print Invoice" target="_blank" class="btn btn-primary btn-xs" href="{{ url('admin/sales/invoice/'.$sales_details->sales_master_id) }}"><i class="fa fa-print"></i> Print</a>
                                                <!-- <a title="ReSend SMS" class="btn btn-warning btn-xs" href="{{ url('/sales/resend-sms/'.$sales_details->sales_master_id) }}"><i class="fa fa-comments-o"></i> ReSend SMS</a>
                                                <a title="Email Invoice" target="" class="btn btn-success btn-xs" href="{{ url('/sales/email-invoice/'.$sales_details->sales_master_id)  }}"><i class="fa fa-envelope"></i> Email Invoice</a>  -->       
                                                
                                                @if(Auth::user()->role == 'superadmin')
                                                    <button type="button" class="inv_delete btn btn-danger btn-xs" data-toggle="modal" data-id="{{ $sales_details->sales_master_id }}" data-target="#myModal">Delete</button>
                                                @endif
                                                
                                            </td>    
                                        </tr>
                                    @endforeach
                                @endif
                                <!-- DELETE MODAL -->
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">DELETE INVOICE</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="text-red">This activity will be added in system log history. Are you sure you want to delete this invoice?</p>
                                                  <form action="{{ url('/sales/delete/invoice') }}" method="post" style="display:inline-block">
                                                        {{ method_field('DELETE') }} {{ csrf_field() }}
                                                        
                                                        <label>Reason to delete</label>
                                                        <textarea rows="2" class="form-control input-sm" name="reason" required=""></textarea>
                                                        <input type="hidden" name="sales_master_id" id="sales_master_id">
                                                        <br>
                                                        <input type="submit" class="btn btn-success btn-sm" value="Submit">                                                    
                                                    </form> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                      </div>
                                      
                                    </div>
                                  </div>
                                  <!-- DELETE MODAL -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
<script>


    $('tbody').delegate('.inv_delete', 'click', function (e) {
        e.preventDefault();
        var sales_master_id = $(this).data('id');
        $('#sales_master_id').val(sales_master_id);

    });

</script>

@endsection