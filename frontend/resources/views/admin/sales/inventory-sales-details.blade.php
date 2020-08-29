@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SALES INVOICE DETAILS 
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales</li>
        <li class="active">Invoice Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}}
                       
                    </div>
                    @endif
                    <div class="">
                        
                        <div class=" ">

                       <div class="panel panel-primary">
                          <div class="panel-heading">Basic Info 
                            @if(Auth::user()->role == 'admin')
                            <a href="" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm"><i class="fa fa-edit" ></i> Edit</a>
                            @endif
                          
                        </div>
                          <div class="panel-body">
                                <form class="form" onsubmit="form_validate()" action="{{ url('admin/sales/memo_details/'.$salesMasters->sales_master_id) }}" method="post">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <table class="table-bordered table-striped" style="width: 100%">
                                            <tr>
                                                <td>Invoice No:</td><td><strong>{{ $globalSettings->invoice_prefix."-BI-".str_pad($salesMasters->sales_master_id, 8, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Invoice Date:</td><td><strong>{{ $salesMasters->sales_date }}</td>
                                            </tr>
                                            <!-- <tr>
                                                <td>Delivery Date:</td><td><strong>{{ $salesMasters->delivery_date }}</td>
                                            </tr> -->
                                            <tr>
                                                <td>Customer:</td><td><strong>{{ $globalSettings->invoice_prefix."-".str_pad($salesMasters->customer_code, 8, '0', STR_PAD_LEFT) }} <br>{{ $salesMasters->customer_name }}@if(!empty($salesMasters->mobile_no))<br>{{ $salesMasters->mobile_no }}@endif</td>
                                            </tr>
                                            <tr>
                                                <td>Total:</td><td><strong>{{  number_format($salesMasters->memo_total,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Discount:</td><td><strong>{{  number_format($salesMasters->discount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Paid:</td><td><strong>{{  number_format($salesMasters->advanced_amount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Due:</td><td><strong>{{  number_format($salesMasters->memo_total-$salesMasters->discount-$salesMasters->advanced_amount,2) }}</td>
                                            </tr>
                                           
                                            <tr>
                                                <td>User:</td><td><strong>@if(isset($sold_by)){{ $sold_by->name }}@endif</td>
                                            </tr>
                                        </table>
                                        

                                    </div>
                                    <div class="col-md-8">
                                        Note: <strong>{!!  $salesMasters->sales_note !!}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <input type="hidden" value="{{ $salesMasters->memo_no }}" name="memo_no" id="memoNo" class="form-control" readonly/>
                                            <input type="hidden" value="{{ $salesMasters->sales_master_id }}" name="sales_master_id"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <input type="hidden" value="{{ $salesMasters->customer_name }}" name="customer_name" id="" class="form-control" readonly/>
                                            <input type="hidden" value="{{ $salesMasters->customer_id }}" name="customer_id"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <input type="hidden" value="@if(isset($sold_by)){{ $sold_by->name }}@endif" name="" id="" class="form-control" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                           
                                            <input type="hidden" name="updated_at" value="{{  $salesMasters->sales_date }}" class="form-control" readonly/>
                                        </div>
                                    </div>

                                    
                                </div>
                          </div>
                        </div>
                           
                            </form>                     
                           
                            <!--START ITEM DETAILS -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            Item Details  <a href="" data-toggle="modal" data-target="#myModal_2" class="btn btn-default btn-sm"><i class="fa fa-plus-circle" ></i> Add new item</a>
                                                
                                            </div> 
                      
                                          <div class="panel-body">
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Item Name</th>
                                                     
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Total</th>
                                                       <!--  <th>Delivery</th> -->
                                                        <th>Action</th>
                                                      <!--  <th>Return Item</th>  -->
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($salesDetail as $key => $salesDetails)
                                                    <tr>
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td>{{ $salesDetails->item_name }}</td>
                                                      
                                                        <td>{{ $salesDetails->stock_out }}</td>
                                                        <td>{{ number_format($salesDetails->sales_price,2) }}</td>
                                                      
                                                        <td>{{ number_format( ($salesDetails->sales_price * $salesDetails->stock_out) ,2)}}</td>
                                                   
                                                        @if(Auth::user()->role == 'admin')
                                                     <!--    
                                                            <a title="Edit" href="{{ url('/sales/invoice/item-unit/'.$salesDetails->sales_master_id."/".$salesDetails->sales_details_id.'/edit') }}"  class="btn btn-info btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                                                        -->
                                                        <td>
                                                        <form action="{{ url('admin/sales/stock/single/'.$salesDetails->stock_id.'/delete') }}" method="post" style="display:inline-block">
                                                            {{ method_field('DELETE') }} {{ csrf_field() }}
                                                            <input type="hidden" name="sales_price" value="{{$salesDetails->sales_price}}">
                                                            <button title="Delete" class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');"  >
                                                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                                            </button>
                                                        </form>
                                                        </td> 
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>          
                            </div>  
                            <!--END ITEM DETAILS-->

                            <!--START ADD LESS DETAILS -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            List Add/Less </div>
                      
                                          <div class="panel-body">
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Particulars</th>
                                                        <th>Amount</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($addLessDetails as $key => $data)
                                                    <tr id="edit_add_less_{{ $data->add_less_id }}">
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td>{{ $data->particular }}</td>
                                                        <td>{{ number_format($data->amount,2) }}</td>

                                                        <td>@if(Auth::user()->role == 'admin')
                                                            <div style="display:flex;">
                                                            <button class="edit_add_less_cls btn btn-warning btn-xs" data-id="{{ $data->add_less_id }}" data-target="#update_add_less">
                                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                            </button>
                                                         
                                                        </div>
                                                        @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>          
                           
                            <!--END ADD LESS DETAILS-->
                             <!--PAYMENT HISTORY-->
                            
                                <!-- <div class="col-md-6">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            Payment History</div>
                                          <div class="panel-body">
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th>Amount</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @foreach($paymentHistory as $key => $data)
                                                    <tr>
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td>{{ $data->transaction_date }}</td>
                                                        <td>{{ $data->tran_ref_name }}</td>
                                                        <td>{{ number_format($data->credit,2) }}</td>
                                                        <td>@if(count($paymentHistory)==$key)
                                                            <a target="_blank" href="{{ url('admin/sales/print/money-receipt/'.$salesMasters->sales_master_id.'/'.$data->voucher_ref) }}" id="print_voucher" class="btn btn-success btn-sm" title="Money Receipt"><i class="fa fa-print"></i> Print</a>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>          
                            </div>  -->
                            <!--PAYMENT HISTORY-->
                            <!-- CHALAN MODAL-->
                            <div id="chalanModal" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Chalan No</h4>
                                  </div>
                                  <div class="modal-body">
                                    <form action="{{ url('/sales/item/delivery/') }}" method="post" style="display:inline-block">
                                        {{ method_field('PUT') }} 
                                        {{ csrf_field() }}
                                        <input type="hidden" name="sales_details_id" value="{{ $salesDetails->sales_details_id }}" id="sales_details_id">
                                        Chalan No: 
                                        <input type="text" autocomplete="OFF" name="chalan_no" value="{{ ++$chalan_no }}" class="form-control input-sm" required="" readonly="">
                                        
                                    
                                  </div>
                                  <div class="modal-footer">
                                    <input type="submit" class="btn btn-success btn-sm " value="Submit">
                                    </form>
                                    <button type="button" class="btn btn-default btn-sm pull-right" data-dismiss="modal">Close</button>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <!-- END CHALAN MODAL -->  
                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->

<!-- START INVOICE EDIT MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT INVOICE</h4>
        </div>
        <div class="modal-body">
            <form action="{{ url('admin/sales/'.$salesMasters->sales_master_id.'/edit/invoice/info') }}" method="post" name="" id="">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
               <div class="form-group">
                    <label for="">Invoice Date</label>
                    <input type="hidden" name="created_at" value="{{$salesMasters->sales_date}}"/>
                    <input type="date" name="updated_at" value="{{$salesMasters->sales_date}}" class="form-control input-sm" required/>
                </div>
                <!-- <div class="form-group">
                    <label for="">Delivery Date</label>
                    <input type="date" name="delivery_date" value="{{$salesMasters->delivery_date}}" class="form-control" required/>
                </div> -->
              <!-- <div class="form-group">
                <label for="">Reference</label>
                <input type="hidden" name="reference_by_old" value="{{ $salesMasters->reference_by }}"/>
                <select name="reference_by" id="reference_by" class="form-control input-sm"  > 
                    <option value="">---Select---</option>
                    @foreach($references as $data)
                        <option value="{{ $data->id }}" {{ $salesMasters->reference_by == $data->id? 'selected' : null }}>{{ $data->name }} </option>
                    @endforeach
                </select>

             </div> -->
              <div class="form-group">
                <label for="">Customer</label>
                <input type="hidden" name="customer_id_old" value="{{ $salesMasters->customer_id }}"/>
                <select name="customer_id" id="customer_id" class="form-control input-sm"  required> 
                    <option value="">---Select---</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customer_id }}" {{ $salesMasters->customer_id == $customer->customer_id? 'selected' : null }}>{{ $customer->customer_name }}-{{$customer->address}}</option>
                    @endforeach
                </select>
             </div>
             <div class="form-group">
                <label for="">Discount Amount</label>
                <input type="hidden" name="discount_old" value="{{ $salesMasters->discount }}"/>
                <input type="text" autocomplete="OFF" name="discount" class="form-control input-sm" value="{{$salesMasters->discount}}">
             </div>
             <div class="form-group">
                <label for="">Advanced Amount</label>
                <input type="hidden" name="advanced_amount_old" value="{{ $salesMasters->advanced_amount }}"/>
                <input type="text" autocomplete="OFF" name="advanced_amount" class="form-control input-sm" value="{{$salesMasters->advanced_amount}}">
             </div>
             <!-- <div class="form-group">
                <label for="">Customization Note</label>
                <textarea name="sales_note" id="ckEditor" class="form-control input-sm">{{ $salesMasters->sales_note}}</textarea>
             </div> -->
             <div class="form-group">
                <input type="submit" id="editInvoice" value="Update" class="btn btn-success">
             </div>
            
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
        </form> 
      </div>
      
    </div>
</div>
<!-- END INVOICE EDIT MODAL -->

<!-- START ADD ITEM MODAL -->
<div class="modal fade" id="myModal_2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-success">ADD NEW ITEM</h4>
        </div>
        <div class="modal-body">
            <form action="{{ url('admin/sales/'.$salesMasters->sales_master_id.'/add-item/') }}" method="post" name="add_item_form">
              {{ csrf_field() }}

         <div class="col-md-6">
              <div class="form-group">
                <label for="">Item</label>
                <select name="item_id" id="item_id" class="form-control input-sm select2" style="width: 100%;"  required> 
                    <option value="">---Select---</option>
                    @foreach($items as $item)
                        <option value="{{ $item->item_id }}">{{$item->item_name}}</option>
                    @endforeach
                </select>
             </div>
         </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="">Quantity</label>
                
                <input type="text" autocomplete="OFF" value="1" name="quantity" id="quantity" class="form-control input-sm" required>
             </div>
         </div>
         <div class="col-md-6">
             <div class="form-group">
                <label for="">Rate</label>
                
                <input type="text" autocomplete="OFF" name="rate" id="rate" class="form-control input-sm" required>
                <input type="hidden" autocomplete="OFF" name="vat" id="vat" value="0" class="form-control input-sm">
             </div>   
            </div>  

         <div class="col-md-6">
             <div class="form-group">
                <label for="">Total</label>
                
                <input type="text" autocomplete="OFF" name="total" id="total" class="form-control input-sm" readonly="">
             </div>
        </div>

        <div class="col-md-12">
            <br>
             <div class="form-group">
                <input type="submit" id="item_submit" value="Submit" class="btn btn-success pull-right">
             </div>
        </div>
            
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        </div>
        </form> 
      </div>
      
    </div>
</div>
<!-- END ADD ITEM MODAL -->


<!-- START ADD LESS UPDATE MODAL -->
<div class="modal modal-default fade" id="update_add_less">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update Add Less</h4>
            </div>
            <form class="form" action="{{url('admin/sales/add-less/update')}}" method="POST" id="update_form">
                <div class="modal-body">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="add_less_id" id="add_less_id" >
                    <div class="form-group">
                        <label for="">Particular</label>
                        <input type="text"  autocomplete="OFF" name="particular" id="particular" class="form-control input-sm"  />
                        @if($errors->has('particular'))
                            <span class="text-danger">{{ $errors->first('particular')}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Amount</label>
                        <input type="hidden" name="amount_old" id="amount_add_less_old" class="form-control input-sm" >
                        <input type="text" autocomplete="OFF" name="amount" id="amount_add_less" class="form-control input-sm" >
                        @if($errors->has('amount'))
                            <span class="text-danger">{{ $errors->first('amount')}}</span>
                        @endif
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning pull-left">Update</button>
                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- // START UPDATE MODAL -->



<script type="text/javascript">
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$(document).ready(function () {
    $('tbody').delegate('.edit_add_less_cls', 'click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        //alert(id);
        $.ajax({
                url:'{{url('admin/sales/add-less-edit/')}}'+"/"+id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $('#add_less_id').val(data.add_less_id);
                    $('#particular').val(data.particular);
                    $('#amount_add_less_old').val(data.amount);
                    $('#amount_add_less').val(data.amount);
                    $('#update_add_less').modal('show');
                },

            });
    });

    $("#quantity").keyup(function() { 
        var qty = $('#quantity').val();
        var rate = $('#rate').val();
        var vat = $('#vat').val();
        $('#total').val(parseFloat(qty)*parseFloat(rate) + parseFloat(qty)*parseFloat(rate) * parseFloat(vat) / 100);
    });

    $("#rate").keyup(function() { 
        var qty = $('#quantity').val();
        var rate = $('#rate').val();
        var vat = $('#vat').val();
        $('#total').val(parseFloat(qty)*parseFloat(rate) + parseFloat(qty)*parseFloat(rate) * parseFloat(vat) / 100);
    });

    $("#vat").keyup(function() { 
        var qty = $('#quantity').val();
        var rate = $('#rate').val();
        var vat = $('#vat').val();
        $('#total').val(parseFloat(qty)*parseFloat(rate) + parseFloat(qty)*parseFloat(rate) * parseFloat(vat) / 100);
    });

    $("#item_submit").click(function() {   //button id
        if(isNaN($('#total').val())){
            alert("Invalid Input");
            event.preventDefault();
        }
    });

    $("#submit").click(function() {   //button id
        var paid = document.getElementById("paid").value;
        var bank_account_id = document.getElementById("bank_account_id").value;
        if(paid!='' && bank_account_id ==''){
            alert("Plaese select payment method");
            event.preventDefault();
        }
    });
});
</script>

<script type="text/javascript">
function calculate() {
    var total = Number($('#total').val());
    var discount = Number($('#discount').val());
    var paid = Number($('#paid').val());
    var due = total - discount - paid;
    $('#due').val(due);

}
</script>

<script>


    $('.chalan').on('click', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        //alert(id);
        $('#sales_details_id').val(id);
    });

</script>
<style type="text/css">
.select2-container
{
    z-index: 999999999999 !important;
    
}
</style>
@if(Session::has('success'))
<!-- <script type="text/javascript">
    window.onload = function(){
        document.getElementById('print_voucher').click();
    }
</script> -->
    @php
        Session::forget('success');
    @endphp
@endif

@endsection