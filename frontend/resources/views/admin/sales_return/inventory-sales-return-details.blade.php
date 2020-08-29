@extends('layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SALES RETURN DETAILS 
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales</li>
        <li class="active">Sales Return Details</li>
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
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                    @endif
                    <div class="">
                        
                        <div class="">

                       <div class="panel panel-primary">
                          <div class="panel-heading">Basic Info 
                            <!--
                            <a href="" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm"><i class="fa fa-edit" ></i> Edit</a>
                            -->
                          </div>
                          <div class="panel-body">
                                <form class="form" onsubmit="form_validate()" action="{{ url('/sales-return/payment/'.$singleSalesReturn->sales_return_master_id.'/update') }}" method="post">
                                    {{ method_field('PUT') }}
                                    {{ csrf_field() }}
                            
                                <div class="row">
                                    <div class="col-md-4">
                                        <table class="table-bordered table-striped" style="width: 100%">
                                            <tr>
                                                <td>Voucher Ref:</td><td><strong>{{ $singleSalesReturn->voucher_ref }}</td>
                                            </tr>
                                            <tr>
                                                <td> Date:</td><td><strong>{{ $singleSalesReturn->date }}</td>
                                            </tr>
                                            <tr>
                                                <td>Customer:</td><td><strong>{{ $singleSalesReturn->customer_name }}@if(!empty($singleSalesReturn->mobile_no)),{{ $singleSalesReturn->mobile_no }}@endif</td>
                                            </tr>
                                            <tr>
                                                <td>Total:</td><td><strong>{{  number_format($singleSalesReturn->memo_total,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Paid:</td><td><strong>{{  number_format($singleSalesReturn->advanced_amount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Discount:</td><td><strong>{{  number_format($singleSalesReturn->discount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Due:</td><td><strong>{{  number_format($singleSalesReturn->memo_total-$singleSalesReturn->advanced_amount+$singleSalesReturn->discount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Received By:</td><td><strong>@if(isset($received_by)){{ $received_by->name }}@endif</td>
                                            </tr>
                                        </table>
                                        

                                    </div>
                                    <div class="col-md-8">
                                        <span class="text-red">Reason:</span> <strong>{{  $singleSalesReturn->reason }}</strong>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">

                                            <input type="hidden" value="{{ $singleSalesReturn->sales_return_master_id }}" name="sales_return_master_id"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <input type="hidden" value="{{ $singleSalesReturn->customer_name }}" name="customer_name" id="" class="form-control" readonly/>
                                            <input type="hidden" value="{{ $singleSalesReturn->customer_id }}" name="customer_id"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            
                                            <input type="hidden" value="@if(isset($received_by)){{ $received_by->name }}@endif" name="" id="" class="form-control" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                           
                                            <input type="hidden" name="updated_at" value="{{  $singleSalesReturn->date }}" class="form-control" readonly/>
                                        </div>
                                    </div>

                                    
                                </div>
                          </div>
                        </div>

                        
                            @if($due!=0)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-danger">
                                      <div class="panel-heading"><i class="fa fa-money"></i> Payable</div>
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
                            
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            Item Details 
                                            <!--
                                            <a href="" data-toggle="modal" data-target="#myModal_2" class="btn btn-default btn-sm"><i class="fa fa-plus-circle" ></i> Add new item</a>
                                            -->
                                          </div>
                      
                                          <div class="panel-body">
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Item Name</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        <th>Item Vat %</th>
                                                        <th>Total</th>
                                                        <!-- <th>Action</th> -->
                                                      <!--  <th>Return Item</th>  -->
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($salesReturnDetails as $key => $value)
                                                    <tr>
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td>{{ $value->item_name }}</td>
                                                        <td>{{ $value->quantity }}</td>
                                                        <td>{{ number_format($value->rate,2) }}</td>
                                                        <td>{{ $value->item_vat }}</td>
                                                        <td>{{ number_format( ($value->rate * $value->quantity) + ($value->item_vat/100) * ($value->rate * $value->quantity),2)}}</td>
                                                     <!--   <td><a href="#" class="btn btn-primary"><i class="fa fa-reply"></i> Return</a></td>-->
                                                     <!--    @if(Auth::user()->role == 'admin')
                                                        <td><a title="Edit" href="{{ url('/sales/invoice/item-unit/'.$value->sales_return_master_id."/".$value->sales_return_details_id.'/edit') }}"  class="btn btn-info btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a></td>
                                                        @endif -->
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>          
                            </div>     
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
            <form action="{{ url('/sales/'.$singleSalesReturn->sales_return_master_id.'/edit/invoice/info') }}" method="post" name="" id="">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
               <div class="form-group">
                    <label for="">Invoice Date</label>
                    <input type="hidden" name="created_at" value="{{$singleSalesReturn->date}}"/>
                    <input type="date" name="updated_at" value="{{$singleSalesReturn->date}}" class="form-control" required/>
                </div>

              <div class="form-group">
                <label for="">Customer</label>
                <input type="hidden" name="customer_id_old" value="{{ $singleSalesReturn->customer_id }}"/>
                <select name="customer_id" id="customer_id" class="form-control"  required> 
                    <option value="">---Select---</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customer_id }}" {{ $singleSalesReturn->customer_id == $customer->customer_id? 'selected' : null }}>{{ $customer->customer_name }}-{{$customer->address}}</option>
                    @endforeach
                </select>
             </div>
             <div class="form-group">
                <label for="">Advanced Amount</label>
                <input type="hidden" name="advanced_amount_old" value="{{ $singleSalesReturn->advanced_amount }}"/>
                <input type="text" autocomplete="OFF" name="advanced_amount" class="form-control" value="{{$singleSalesReturn->advanced_amount}}">
             </div>
             <div class="form-group">
                <label for="">Reason</label>
                <textarea name="reason" id="ckEditor" class="form-control input-sm">{{ $singleSalesReturn->reason}}</textarea>
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
            <form action="{{ url('/sales/'.$singleSalesReturn->sales_return_master_id.'/add-item/') }}" method="post" name="add_item_form">
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


@endsection