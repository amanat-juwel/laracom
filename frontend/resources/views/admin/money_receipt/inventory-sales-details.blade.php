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
                          <div class="panel-heading">Basic Info <a href="" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm"><i class="fa fa-edit" ></i> Edit</a></div>
                          <div class="panel-body">
                                <form class="form" onsubmit="form_validate()" action="{{ url('admin/sales/memo_details/'.$salesMasters->sales_master_id) }}" method="post">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <table class="table-bordered table-striped" style="width: 100%">
                                            <tr>
                                                <td>Invoice No:</td><td><strong>{{ $salesMasters->voucher_ref }}</td>
                                            </tr>
                                            <tr>
                                                <td>Memo No:</td><td><strong>{{ $salesMasters->memo_no }}</td>
                                            </tr>
                                            <tr>
                                                <td>Invoice Date:</td><td><strong>{{ $salesMasters->sales_date }}</td>
                                            </tr>
                                            <tr>
                                                <td>Delivery Date:</td><td><strong>{{ $salesMasters->delivery_date }}</td>
                                            </tr>
                                            <tr>
                                                <td>Customer:</td><td><strong>{{ "SFL-".str_pad($salesMasters->customer_code, 8, '0', STR_PAD_LEFT) }} <br>{{ $salesMasters->customer_name }}@if(!empty($salesMasters->mobile_no))<br>{{ $salesMasters->mobile_no }}@endif</td>
                                            </tr>
                                            <tr>
                                                <td>Total:</td><td><strong>{{  number_format($salesMasters->memo_total,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Paid:</td><td><strong>{{  number_format($salesMasters->advanced_amount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Discount:</td><td><strong>{{  number_format($salesMasters->discount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Sales Executive :</td><td><strong>@if(isset($salesMasters->reference_by)){{ $salesMasters->reference_by }}@endif</td>
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

                        
                            @if($due!=0)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-danger">
                                      <div class="panel-heading"><i class="fa fa-money"></i> Receivable</div>
                                      <div class="panel-body">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Tran. Date</label>
                                                <input type="text" id="datepicker" name="updated_at" value="{{ date('Y-m-d') }}" class="form-control input-sm" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Total Payable</label>
                                                <input type="text" value="{{ $due }}" name="payable"  id="total" class="form-control input-sm" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Due</label>
                                                <input type="text" value="{{ $due }}" name="due"  id="due" class="form-control input-sm" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Discount</label>
                                                <input type="text" name="discount" value=""  id="discount" class="form-control input-sm" onkeyup="calculate()" onchange="calculate()"/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input type="text" autocomplete="OFF" name="paid"  id="paid" class="form-control input-sm" onkeyup="calculate()" onchange="calculate()" required/>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Particular</label>
                                                <input type="text" name="particular" value="Cash" class="form-control" readonly/>
                                            </div>
                                        </div>
                                        -->
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Payment Method</label>
                                                <select name="bank_account_id" id="bank_account_id" class="form-control input-sm">
                                                    @foreach($accounts as $key => $account)
                                                    <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <button type="submit" class="btn btn-danger" name="submit" id="submit" ><i class="fa fa-money" ></i> Submit </button>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                   
                                </div>
                            </div> 
                            @endif 
                            </form>                     
                            
                            <!--PAYMENT HISTORY-->
                            <div class="row">
                                <div class="col-md-6">
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
                                                            <a target="_blank" href="{{ url('sales/print/money-receipt/'.$salesMasters->sales_master_id.'/'.$data->voucher_ref) }}" id="print_voucher" class="btn btn-success btn-sm" title="Money Receipt"><i class="fa fa-print"></i> Print</a>
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
                            </div> 
                            <!--PAYMENT HISTORY-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            Item Details <a href="" data-toggle="modal" data-target="#myModal_2" class="btn btn-default btn-sm"><i class="fa fa-plus-circle" ></i> Add new item</a></div>
                      
                                          <div class="panel-body">
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Item Name</th>
                                                        <th>Image</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Item Vat %</th>
                                                        <th>Total</th>
                                                        <th>Delivery</th>
                                                        <th>Action</th>
                                                      <!--  <th>Return Item</th>  -->
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($salesDetail as $key => $salesDetails)
                                                    <tr>
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td>{{ $salesDetails->item_name }}</td>
                                                        <td>@if($salesDetails->item_image != '')
                                                            <a href="{{ url($salesDetails->item_image) }}" target="_blank">View</a>
                                                            @else
                                                            None
                                                            @endif
                                                        </td>
                                                        <td>{{ $salesDetails->quantity }}</td>
                                                        <td>{{ number_format($salesDetails->sales_price,2) }}</td>
                                                        <td>{{ $salesDetails->item_vat }}</td>
                                                        <td>{{ number_format( ($salesDetails->sales_price * $salesDetails->quantity) + ($salesDetails->item_vat/100) * ($salesDetails->sales_price * $salesDetails->quantity),2)}}</td>
                                                        @if($salesDetails->is_delivered == 'yes')
                                                        <td class="btn btn-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Chalan No: {{ $salesDetails->chalan_no }}</td>
                                                        @else
                                                        <td class="btn btn-danger btn-sm"><i class="fa fa-close" aria-hidden="true"></i> Not-Complete</td>
                                                        @endif
                                                        
                                                     <!--   <td><a href="#" class="btn btn-primary"><i class="fa fa-reply"></i> Return</a></td>-->
                                                        @if(Auth::user()->role == 'admin')
                                                        <td>
                                                            <a title="Edit" href="{{ url('/sales/invoice/item-unit/'.$salesDetails->sales_master_id."/".$salesDetails->sales_details_id.'/edit') }}"  class="btn btn-info btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                                                            @if($salesDetails->is_delivered != 'yes')
                                                            
                                                            <button type="button" class="chalan btn btn-success btn-sm"  onclick="return confirm('Are you sure you want to Deliver this item?');" data-id="{{ $salesDetails->sales_details_id }}" data-toggle="modal" data-target="#chalanModal">
                                                                <i class="fa fa-truck" aria-hidden="true"></i> Mark as Delivered</button>
                                                            @endif

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
            <form action="{{ url('/sales/'.$salesMasters->sales_master_id.'/edit/invoice/info') }}" method="post" name="" id="">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
               <div class="form-group">
                    <label for="">Invoice Date</label>
                    <input type="hidden" name="created_at" value="{{$salesMasters->sales_date}}"/>
                    <input type="date" name="updated_at" value="{{$salesMasters->sales_date}}" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label for="">Delivery Date</label>
                    <input type="date" name="delivery_date" value="{{$salesMasters->delivery_date}}" class="form-control" required/>
                </div>
              <div class="form-group">
                <label for="">Reference</label>
                <input type="hidden" name="reference_by_old" value="{{ $salesMasters->reference_by }}"/>
                <select name="reference_by" id="" class="form-control" > 
                    <option value="">---Select---</option>
                    @foreach($references as $data)
                        <option value="{{ $data->expense_head_id }}" {{ $salesMasters->reference_by == $data->expense_head_id? 'selected' : null }}>{{ $data->expense_head }}</option>
                    @endforeach
                </select>
             </div>
              <div class="form-group">
                <label for="">Customer</label>
                <input type="hidden" name="customer_id_old" value="{{ $salesMasters->customer_id }}"/>
                <select name="customer_id" id="customer_id" class="form-control"  required> 
                    <option value="">---Select---</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customer_id }}" {{ $salesMasters->customer_id == $customer->customer_id? 'selected' : null }}>{{ $customer->customer_name }}-{{$customer->address}}</option>
                    @endforeach
                </select>
             </div>
             <div class="form-group">
                <label for="">Advanced Amount</label>
                <input type="hidden" name="advanced_amount_old" value="{{ $salesMasters->advanced_amount }}"/>
                <input type="text" autocomplete="OFF" name="advanced_amount" class="form-control" value="{{$salesMasters->advanced_amount}}">
             </div>
             <div class="form-group">
                <label for="">Customization Note</label>
                <textarea name="sales_note" id="ckEditor" class="form-control input-sm">{{ $salesMasters->sales_note}}</textarea>
             </div>
             <div class="form-group">
                <input type="submit" id="editInvoice" value="Update" class="btn btn-success">
             </div>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
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
            <form action="{{ url('/sales/'.$salesMasters->sales_master_id.'/add-item/') }}" method="post" name="add_item_form">
              {{ csrf_field() }}

            <div class="col-md-6">
              <div class="form-group">
                <label for="">Item</label>
                <select name="item_id" id="item_id" class="form-control"  required> 
                    <option value="">---Select---</option>
                    @foreach($items as $item)
                        <option value="{{ $item->item_id }}">{{ $item->item_name }}-{{$item->item_code}}</option>
                    @endforeach
                </select>
             </div>
         </div>
         <div class="col-md-6">
             <div class="form-group">
                <label for="">Warehouse</label>
                <select name="stock_location_id" class="form-control " required="" > 
                    <option value="">---Select---</option>
                    @foreach($stock_locations as $st)
                        <option value="{{ $st->stock_location_id }}">{{ $st->stock_location_name }}</option>
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
             </div>   
            </div>  
            <div class="col-md-6">         
             <div class="form-group">
                <label for="">Vat %</label>
                
                <input type="text" autocomplete="OFF" name="vat" id="vat" value="0" class="form-control input-sm">
             </div>
         </div>
         <div class="col-md-6">
             <div class="form-group">
                <label for="">Total</label>
                
                <input type="text" autocomplete="OFF" name="total" id="total" class="form-control input-sm" readonly="">
             </div>
        </div>
        <div class="col-md-6">
             <div class="form-group">
                <label for="">Paid</label>
                
                <input type="text" autocomplete="OFF" name="advanced_amount" class="form-control input-sm">
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

<script type="text/javascript">
$(document).ready(function () {

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

@if(Session::has('success'))
<script type="text/javascript">
    window.onload = function(){
        document.getElementById('print_voucher').click();
    }
</script>
    @php
        Session::forget('success');
    @endphp
@endif

@endsection