@extends('admin.layouts.template')

@section('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endsection

@section('template')

<section class="content-header">
    <h1>
        SALES <a target="" id="" style="color: #fff" class="btn btn-warning btn-sm" href="{{ url('/admin/sales/sales-details/') }}"><i class="fa fa-list"></i> Sales List</a>
        
        </h1>
    <ol class="breadcrumb">
        
        <li> <a target="_blank" id="print_invoice" style="color: white" class="btn btn-danger btn-sm" href="{{ url('/admin/sales/invoice/'.$lastSalesMasterNo) }}"><i class="fa fa-print"></i> Print Last Invoice</a></li>

    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <!--ADD ROW -->
            <div class="row">
                <form class="form" action="{{ url('admin/sales/store') }}" method="post" id="yoyo" >
                {{ csrf_field() }}
                <div class="col-md-12">
                    <!-- @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}}
                       
                    </div>
                    @endif -->
                    <div class="panel panel-primary">
                        <!-- <div class="panel-heading">
                            Info
                        </div> -->
                        <div class="panel-body" style="padding: 5px">
                            <div class="col-md-2">
                                            Invoice Date: 
                                            <div class="form-group">
                                                <input type="text" autocomplete="OFF" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="sales_date" id="sales_datememoNo" class=" form-control input-sm" required/>
                                            </div>
                            </div>
                            <div class="col-md-1">
                                            Invoice 
                                            <div class="form-group">
                                                <input type="text" autocomplete="OFF" name="memo_no" value="{{++$lastSalesMasterNo}}" class="form-control input-sm" readonly="" />
                                                <input type="hidden" autocomplete="OFF" name="chalan_no" value="{{++$chalan_no}}"  class="form-control input-sm" readonly="" />
                                            </div>
                            </div>
                            <!-- <div class="col-md-1">
                                            Chalan 
                                            <div class="form-group">
                                                <input type="hidden" autocomplete="OFF" name="chalan_no" value="{{++$chalan_no}}"  class="form-control input-sm" readonly="" />
                                            </div>
                            </div> -->
                            <!-- <div class="col-md-2">
                                            Sales Executive: 
                                            <div class="form-group">
                                                <select name="reference_by" id="reference_by" class="form-control select2"  > 
                                                    <option value="">---Select---</option>
                                                    @foreach($references as $data)
                                                        <option value="{{ $data->id }}">{{ $data->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                            </div> -->
                            <div class="col-md-4">
                                            Customer: 
                                            <div class="form-group">
                                                <select name="customer_id" id="customer_id" class="form-control select2"  > 
                                                    <option value=''>---Select---</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->customer_id }}">{{  $globalSettings->invoice_prefix."-".str_pad($customer->customer_code, 4, '0', STR_PAD_LEFT) }} | {{$customer->customer_name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                            </div>
                            <div class="col-md-2">
                                <br>
                                            <!-- <a data-toggle="modal" class="btn btn-info btn-sm" data-target="#myModal"><i class="fa fa-plus"></i> Add New Customer</a> -->
                            </div>
                            <!-- <div class="col-md-2">
                                <br>
                                            <a data-toggle="modal" class="btn btn-warning btn-sm" data-target="#noteModal"><i class="fa fa-gears"></i> Customization</a>
                            </div> -->
                            

                                            <!-- ADD INSTANT CUSTOMER -->
                                            <div id="myModal" class="modal fade" role="dialog">
                                                  <div class="modal-dialog">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">New Customer</h4>
                                                      </div>
                                                      <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                                                                    <div class="form-group">
                                                                        <label for="">Name (Required)</label>
                                                                        <input type="text" autocomplete="OFF" name="customer_name" id="customer_name" class="remove-slash form-control input-sm" placeholder="Customer Name"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                                                                    <div class="form-group">
                                                                        <label for="">Mobile (Required)</label>
                                                                        <input type="text" autocomplete="OFF" name="mobile_no" id="mobile_no" class="form-control input-sm" placeholder="ex: 01819363636" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                                                                    <div class="form-group">
                                                                        <label for="">Address (Required)</label>
                                                                        <input type="text" autocomplete="OFF" name="address" id="address" class="form-control input-sm" placeholder="Address"/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                                                                    <div class="form-group">
                                                                        <label for="">Email (Optional)</label>
                                                                        <input type="email" autocomplete="OFF" name="email" id="email" class="form-control input-sm" placeholder="Email "/>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                                                                    <div class="form-group">
                                                                        <label for="">Category (Required)</label><br>
                                                                        <select name="category" id="cust_category" class="form-control " > 
                                                                            
                                                                            @foreach($customer_categories as $data)
                                                                                <option value="{{ $data->id }}">{{$data->cat_name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" id="newCustomerBtn" class="btn btn-success" data-dismiss="modal"><i class="fa fa-download"></i> save</button>
                                                      </div>
                                                    </div>

                                                  </div>
                                                </div>   

                                            <!-- //ADD INSTANT CUSTOMER -->
                                      
                                       
                                
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="padding: 0">&nbsp Cart</div>
                        <div class="panel-body" style="padding: 5px">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="margin-bottom: 0px">
                                        <thead>
                                            <tr>
                                                <th style="width:3%">#</th>
                                                <th style="width:27%">Item</th>
                                                <th style="width:8%">Stock</th>
                                                <th style="width:23%">Description</th>
                                                <th style="width:9%">Qty</th>
                                                <th style="width:11%">Rate</th>
                                                <!-- <th style="width:9%">Delivery</th> -->
                                                <th style="width:13%">Amount</th>
                                                <th style="width:6%"><!-- Delete --></th>
                                            </tr>
                                        </thead>
                                        <tbody class="neworderbody">
                                            <tr>
                                                <td class="no">1</td>
                                                <td>
                                                    <select name="product_id[]" class="form-control product_id select2" id="firstRowProduct" required=""> 
                                                        <option value="">---Select---</option>
                                                        @foreach($items as $item)
                                                            @if($item->stock_in-$item->stock_out > 0)
                                                            <option data-price="@if($item->discounted_price==null){{ $item->mrp }}@else{{ $item->discounted_price }}@endif" data-availableQty="{{ $item->stock_in-$item->stock_out }}" value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="text"  class="form-control input-sm availableQty"  name="availableQty[]" readonly="">
                                                </td>
                                                <td>
                                                    <textarea rows="1" class="item_note form-control input-sm" name="item_note[]"></textarea>
                                                </td>
                                                <td>
                                                    <input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" required="">
                                                </td>
                                                <td>
                                                    <input type="text" autocomplete="OFF" class="price form-control input-sm" name="price[]" id="firstRowPrice" value="" required="">
                                                    <input type="hidden" class="dis form-control input-sm" name="dis[]" value="{{$globalSettings->vat_percent}}" >
                                                </td>
                                                <!-- <td>
                                                    
                                                    <select name="is_delivered[]" class="form-control is_delivered input-sm" id="firstRowIsCustomized" required=""> 
                                                        <option value="no">No</option>
                                                        <option value="yes">Yes</option>
                                                        
                                                    </select>

                                                </td> -->
                                                <td>
                                                    <input type="text" class="amount form-control input-sm" name="amount[]" readonly="">
                                                </td>
                                                <td>
                                                    <!-- <input type="button" class="btn btn-danger delete" value="x"> -->
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <td colspan="6" style="font-size: 16px" class="text-danger"> Qty: <b class="total_qty">0</b> | Amount: <b class="total">0</b> | Vat({{$globalSettings->vat_percent}}%): <b class="vat_cls">0</b></td>
                                        </tfoot>
                                    </table> 
                                </div>   
                                <input type="button" class="btn btn-info btn-sm add " id="add_new_item" value="Add New Item">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="box box-danger collapsed-box box-solid">
                    <div class="box-header with-border" >
                      <div class="">&nbsp Add/Less</div>

                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body" style="padding: 5px">
                      <table class="table table-striped" style="margin-bottom: 0px">
                        <thead>
                            <tr>
                                <th style="width:10%">#</th>
                                <th style="width:60%">Particulars</th>
                                <th style="width:20%">Amount</th>
                                <th style="width:10%"><!-- Delete --></th>
                            </tr>
                        </thead>
                        <tbody class="neworderbody_for_add_less">
                            <tr>
                                <td class="no">1</td>
                                <td>
                                    <input type="text" autocomplete="OFF" class="particulars form-control input-sm" name="particulars[]" >
                                </td>
                                <td>
                                    <input type="text" autocomplete="OFF" class="amount_add_less form-control input-sm" name="amount_add_less[]" >
                                </td>
                                <td>
                                    <!-- <input type="button" class="btn btn-danger delete" value="x"> -->
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <!-- <td colspan="6" style="font-size: 16px" class="text-danger"> Qty: <b class="total_qty">0</b> | Amount: <b class="total">0</b> | Vat({{$globalSettings->vat_percent}}%): <b class="vat_cls">0</b></td> -->
                        </tfoot>
                    </table>    
                    <input type="button" class="btn btn-info btn-sm addLess" id="add_new_add_less" value="Add New Row">
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0">&nbsp Payment</div>
                        <div class="panel-body" style="padding: 5px">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Total</label>
                                                <input type="text" name="memo_total" id="totalPrice" class="form-control input-sm totalPrice" readonly="" />
                                                <!-- @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif -->
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Discount Mode</label>
                                                <select name="discount_mode"  id="discount_mode" class="form-control input-sm" />
                                                    <option value="Percentage">Percentage (%)</option>
                                                    <option value="TK">TK</option>
                                                </select>
                                                <!-- @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif -->
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Discount</label>
                                                <input type="text" autocomplete="OFF" name="discount"  id="discount" class="form-control input-sm" value=""/>
                                                @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif 
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount Paid*</label>
                                                <input type="text" autocomplete="OFF" name="advanced_amount" id="advancePaid"  class="form-control input-sm"  />
                                                @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif 
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Due</label>
                                                <input type="text"  id="dueAmount" name="due" class="form-control input-sm" readonly="" />
                                                 @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif 
                                            </div>
                                        </div> 
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Payment Method*</label>
                                                <select name="bank_account_id" id="bank_account_id" class="form-control input-sm " >
                                                    <!--<option value="">--- Select ---</option>-->
                                                    @foreach($accounts as $key => $account)
                                                    <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Remarks</label>
                                                <input type="text" autocomplete="OFF" name="remarks" value=""  class="form-control input-sm" />
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <br>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success" name="submit" id="submit" value="Submit">
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
            <!-- // ADD ROW -->
        </div>
    </div>

</section>
<!-- End Main Content -->

<!-- Disable Double click submit -->
<script type="text/javascript">
    $('form').submit(function() {
      $(this).find("input[type='submit']").prop('disabled',true);
    });
</script>
<!-- ./Disable Double click submit -->

<script type="text/javascript">

    $('#discount_mode').val("{{$globalSettings->discount_mode}}");

    function totalAmount(){
        var t = 0;
        var t_qty = 0;
        $('.amount').each(function(i,e){
            var amt = $(this).val()-0;
            t += amt;
        });
        $('.qty').each(function(i,e){
            var qty = $(this).val()-0;
            t_qty += qty;
        });

        var total_add_less = 0;
        $('.amount_add_less').each(function(i,e){
            var amt = parseFloat($(this).val());
            total_add_less += amt;
        });

        if(isNaN(total_add_less)){
            total_add_less = 0;
        }

        x=t;
        t = t + t*({{$globalSettings->vat_percent}})/100;

        $('.total').html(x);
        $('.total_qty').html(t_qty);
        var v = parseFloat(t)-parseFloat(x);
        $('.vat_cls').html(v);
        $('#totalPrice').val(t+total_add_less);
        $('#dueAmount').val(t);
        
    }
    $(function () {
        $('.getmoney').change(function(){
            var total = $('.total').html();
            var getmoney = $(this).val();
            var t = getmoney - total;
            $('.backmoney').val(t).toFixed(2);
        });
        $('#advancePaid').keyup(function(){
            $('#bank_account_id').val(4);
            var memo_total = parseFloat($('#totalPrice').val());
            var memo_total = parseFloat($('#totalPrice').val());
            var memo_total_with_out_vat = parseFloat($('#totalPrice').val())*100/(100+{{$globalSettings->vat_percent}});
            var discount_mode = $('#discount_mode').val();
            if(discount_mode=='Percentage'){
                var discount_percent = parseFloat($('#discount').val());
                var discount = (memo_total_with_out_vat*discount_percent)/100;
            }
            else{
                var discount = parseFloat($('#discount').val());
            }

            if(isNaN(discount)){
                $('#discount').val(0);
                discount = 0;
            }
            var advancePaid = parseFloat($('#advancePaid').val());
            if(isNaN(advancePaid)){
                $('#advancePaid').val();
                advancePaid = 0;
                $('#bank_account_id').val('');
            }
            if(advancePaid > memo_total){
                alert("Amount can not exceed invoice total");
                $('#advancePaid').val('');
                advancePaid = 0;
                $('#bank_account_id').val('');
            }
            var due = memo_total - discount - advancePaid;
            $('#dueAmount').val(due);
        });
        $('#discount').keyup(function(){
            var advancePaid = parseFloat($('#advancePaid').val());
            var memo_total = parseFloat($('#totalPrice').val());
            var memo_total_with_out_vat = parseFloat($('#totalPrice').val())*100/(100+{{$globalSettings->vat_percent}});
            var discount_mode = $('#discount_mode').val();
            if(discount_mode=='Percentage'){
                var discount_percent = parseFloat($('#discount').val());
                var discount = Math.ceil((memo_total_with_out_vat*discount_percent)/100);

                if(discount_percent>100){
                    alert('ALERT: WRONG INPUT. Discount percentage can not exceed 100 % ');
                    $('#discount').val('');
                }
                else if(discount_percent>30){
                    alert('ALERT: You are giving more than 30 % discount!');
                }

            }
            else{
                var discount = parseFloat($('#discount').val());
                if(discount>memo_total){
                    alert('ALERT: WRONG INPUT. Discount can not exceed Invoice Total');
                    $('#discount').val('')
                }
            }

            if(isNaN(discount)){
                $('#discount').val('');
                discount = 0;
            }

            if(isNaN(advancePaid)){
                //advancePaid = memo_total;
                advancePaid = 0;
            }
            //alert(advancePaid);
            //var due = 0;//memo_total - discount - advancePaid;
            $('#dueAmount').val(memo_total - discount - advancePaid);

            //var advancePaid = memo_total - discount;
            //$('#advancePaid').val(advancePaid);
        });

        $('#discount_mode').change(function(){
            var advancePaid = parseFloat($('#advancePaid').val());
            var memo_total = parseFloat($('#totalPrice').val());
            var memo_total_with_out_vat = parseFloat($('#totalPrice').val())*100/(100+{{$globalSettings->vat_percent}});
            var discount_mode = $('#discount_mode').val();
            if(discount_mode=='Percentage'){
                var discount_percent = parseFloat($('#discount').val());
                var discount = Math.ceil((memo_total_with_out_vat*discount_percent)/100);

                if(discount_percent>100){
                    $('#discount').val('');
                    $('#dueAmount').val(memo_total - advancePaid);
                    alert('ALERT: WRONG INPUT. Discount percentage can not exceed 100 % ');
                }
                else if(discount_percent>30){
                    alert('ALERT: You are giving more than 30 % discount!');
                }
                

            }
            else{
                var discount = parseFloat($('#discount').val());
            }

            if(isNaN(discount)){
                discount = 0;
            }

            if(isNaN(advancePaid)){
                advancePaid = 0;
            }

            //var due = 0;//memo_total - discount - advancePaid;
            $('#dueAmount').val(memo_total - discount - advancePaid);

            
            //$('#advancePaid').val(advancePaid);
        });

        $('#discount').blur(function(){
           
            var advancePaid = parseFloat($('#advancePaid').val());
            var memo_total = parseFloat($('#totalPrice').val());
            var memo_total_with_out_vat = parseFloat($('#totalPrice').val())*100/(100+{{$globalSettings->vat_percent}});
            var discount_mode = $('#discount_mode').val();
            if(discount_mode=='Percentage'){
                var discount_percent = parseFloat($('#discount').val());
                var discount = Math.ceil((memo_total_with_out_vat*discount_percent)/100);
            }
            else{
                var discount = parseFloat($('#discount').val());
            }

            if(isNaN(discount)){
                advancePaid = 0;
                discount = 0;
            }
            if(isNaN(advancePaid)){
                advancePaid = 0;
            }

            $('#dueAmount').val(memo_total - discount - advancePaid);         
            
        });
        $('.add').click(function () {
            
            if($('#firstRowProduct').val() == ''){
                alert('Select an item first');
            }

            else if($('#totalPrice').val() > 0){
                var product = $('.product_id').html();
               
                // var is_delivered = $('.is_delivered').html();
    
                var n = ($('.neworderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' + '<td><select class="form-control product_id select2" name="product_id[]" required>' + product + '</select></td>' +
                    '<td><input type="text" autocomplete="OFF" class="form-control input-sm availableQty"  name="availableQty[]" readonly ></td>'+
                    '<td><textarea rows="1" class="item_note form-control input-sm" name="item_note[]"></textarea></td>'+
                    '<td><input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" required></td>' +
                    '<td><input type="text" autocomplete="OFF" class="price form-control input-sm" name="price[]" value="" required></td></td><input type="hidden" class="dis form-control input-sm" name="dis[]" value="{{$globalSettings->vat_percent}}" >' +

                    // '<td><select class="form-control is_delivered input-sm" name="is_delivered[]" required>' + is_delivered + '</select></td>'+
                    '<td><input type="text" class="amount form-control input-sm" name="amount[]" readonly></td>' +
                    '<td><input type="button" class="btn btn-danger btn-sm delete" value="x"></td></tr>';
                $('.neworderbody').append(tr);
                Initialize();
            }
            else{
                $('#firstRowPrice').focus();
            }
        });
        $('.neworderbody').delegate('.delete', 'click', function () {
            $(this).parent().parent().remove();
            totalAmount();
        });
        $('.neworderbody').delegate('.product_id', 'change', function () {
            var tr = $(this).parent().parent();
            var price = tr.find('.product_id option:selected').attr('data-price');
            tr.find('.price').val(price);

            var availableQty = tr.find('.product_id option:selected').attr('data-availableQty');
            tr.find('.availableQty').val(availableQty);
            
            var qty = tr.find('.qty').val() - 0;
            var dis = 0;
            var price = tr.find('.price').val() - 0;
        
            var total = (qty * price) ;
            tr.find('.amount').val(total);
            totalAmount();
        });
        
        $('.neworderbody').delegate('.qty ,.price, .dis', 'keyup', function () {
            var tr = $(this).parent().parent();
            var qty = tr.find('.qty').val() - 0;
            var dis = 0;
            var price = tr.find('.price').val() - 0;
            var availableQty = tr.find('.availableQty').val() - 0;
      

           if(qty>availableQty){      
                alert('Stock not available as required in this location! Current Available qty is: '+availableQty)     
                tr.find('.qty').val('');
            }

            var total = (qty * price) ;
            tr.find('.amount').val(total);
            totalAmount();
        });
        
        $('#hideshow').on('click', function(event) {  
             $('#content').removeClass('hidden');
             $('#content').addClass('show'); 
             $('#content').toggle('show');
        });

        
    });

$(document).keypress(
        function(event){
         if (event.which == '13') {
            //ADDING NEW ROW ON ENTER CLICK
            if($('#firstRowProduct').val() == ''){
                alert('Select an item first');
            }

            else if($('#totalPrice').val() > 0){
                var product = $('.product_id').html();
              
                // var is_delivered = $('.is_delivered').html();
                
                var n = ($('.neworderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' + '<td><select class="form-control product_id select2" name="product_id[]" required>' + product + '</select></td>' +
                '<td><input type="text" autocomplete="OFF" class="form-control input-sm availableQty"  name="availableQty[]" readonly ></td>'+
                '<td><textarea rows="1" class="item_note form-control input-sm" name="item_note[]"></textarea></td>'+
                    // '<td><select class="form-control is_delivered input-sm" name="is_delivered[]" required>' + is_delivered + '</select></td>'+
                  
                    '<td><input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" required></td>' +
                    '<td><input type="text" class="price form-control input-sm" name="price[]" value="" required></td></td><input type="hidden" class="dis form-control input-sm" name="dis[]" value="{{$globalSettings->vat_percent}}" >' +

                    '<td><input type="text" class="amount form-control input-sm" name="amount[]" readonly></td>' +
                    '<td><input type="button" class="btn btn-danger btn-sm delete" value="x"></td></tr>';
                $('.neworderbody').append(tr);
                Initialize();
            }
            else{
                $('#firstRowPrice').focus();
            }
            //ADDING NEW ROW ON ENTER CLICK
            setTimeout(function() { $('.product_id').focus();}, 100);
            //setTimeout(function() { $('.product_id').trigger("click");}, 100);
            event.preventDefault();
          }


    });

    $('#newCustomerBtn').click(function () {

            var customer_name = $('#customer_name').val();
            var mobile_no = $('#mobile_no').val();
            var address = $('#address').val();
            var email = $('#email').val();
            var category = $('#cust_category').val();
            
            if(address==''){
                address="NULL";
            }
            if(email==''){
                email="NULL";
            }
            
            var URL = "{{url('/admin/customer/quick/')}}"+"/"+customer_name+"/"+mobile_no+"/"+address.replace(/\//g, '|||')+"/"+email+"/"+category ;
            
            console.log(URL);
            if((customer_name!='' && mobile_no=='') || (customer_name=='' && mobile_no!='')){
                event.preventDefault();
                alert('Failed! Customer Name or Mobile No is missing');
            }
            
            else if(customer_name!='' && mobile_no!=''){
                $.ajax({
                url : URL,
                type : 'GET',
                dataType:"json",    
                success:function(data){
                        console.log(data);
                        $('#customer_id').append( '<option value="'+data.customer_id+'" selected>'+data.customer_code+' | '+customer_name+'</option>' );

                        $('#customer_name').val('');
                        $('#mobile_no').val('');
                    }
                });
            }          
        });
</script>

<!-- START ADD/LESS -->
<script type="text/javascript">

    function totalAmountAddLess(){
        var total_add_less = 0;
        $('.amount_add_less').each(function(i,e){
            var amt = parseFloat($(this).val());
            total_add_less += amt;
        });

        var total_add_less = parseFloat(total_add_less);

        if(isNaN(total_add_less)){
            total_add_less = 0;
        }
        var total_cls = parseFloat($('.total').text());
        var vat_cls = parseFloat($('.vat_cls').text());

        console.log(total_cls+vat_cls+total_add_less);

        $('#totalPrice').val(total_cls+vat_cls+total_add_less);

        var memo_total_with_out_vat = parseFloat($('#totalPrice').val())*100/(100+{{$globalSettings->vat_percent}}); 
        var discount_mode = $('#discount_mode').val();
        if(discount_mode=='Percentage'){
            var discount_percent = parseFloat($('#discount').val());
            var discount = Math.ceil((memo_total_with_out_vat*discount_percent)/100);
        }
        else{
            var discount = parseFloat($('#discount').val());
        }
        var advancePaid = $('#advancePaid').val();

        if(isNaN(discount)){
            advancePaid = 0;
            discount = 0;
        }
        if(isNaN(advancePaid)){
            advancePaid = 0;
        }

        $('#dueAmount').val(parseFloat($('#totalPrice').val())-discount-advancePaid);

    }

    $(function () {
        
        $('.addLess').click(function () {
        
            var n = ($('.neworderbody_for_add_less tr').length - 0) + 1;
            var tr = '<tr><td class="no">' + n + '</td>' + 
                '<td><input type="text" autocomplete="OFF" class="particulars form-control input-sm" name="particulars[]" required></td>'+
                '<td><input type="text" autocomplete="OFF" class="amount_add_less form-control input-sm" name="amount_add_less[]" required></td>' +
                '<td><input type="button" class="btn btn-danger btn-sm delete" value="x"></td></tr>';
            $('.neworderbody_for_add_less').append(tr);

           
        });

        $('.neworderbody_for_add_less').delegate('.amount_add_less', 'keyup', function () {
            var tr = $(this).parent().parent();
            var amount_add_less = tr.find('.amount_add_less').val() - 0;

            console.log(amount_add_less);
            if(!isNaN(parseFloat(amount_add_less))){
                totalAmountAddLess();
            }
            
        });

        $('.neworderbody_for_add_less').delegate('.delete', 'click', function () {
            $(this).parent().parent().remove();
            totalAmountAddLess();
        });

        
    });
</script>
<!-- END ADD/LESS -->

<script>
$(function() {
  $(".remove-slash").on("keyup", function(event) {
    var value = $(this).val();
    if (value.indexOf('/') != -1) {
      $(this).val(value.replace(/\//g, ""));
    }
  })
});


$(document).ready(function () {
       $("#mobile_no").keyup(function() {
            if($("#mobile_no").val()=='8'){
                $("#mobile_no").val('');
            }
       });
       $("#submit").click(function() {   //button id
            var due = $('#dueAmount').val();
            var advancePaid = parseInt($('#advancePaid').val());
            var bank_account_id = parseInt($('#bank_account_id').val());
            var mobile_no = document.getElementById("mobile_no").value;
            var customer_name = document.getElementById("customer_name").value;

            
            
            if(due < 0 || isNaN(due)){
                alert("Wrong amount input");
                $('#advancePaid').focus();
                event.preventDefault();
            }
            else if(advancePaid > 0 && isNaN(bank_account_id)){
                alert("Plaese select payment method");
                event.preventDefault();
            }
            else if(mobile_no!='' && customer_name ==''){
                alert("Customer name missing!");
                event.preventDefault();
            }
            else if(mobile_no=='' && customer_name !=''){
                alert("Customer Mobile No missing!");
                event.preventDefault();
            }

        });// End of submit

   });

</script> 
<script>
    function Initialize() {
        $('.select2').select2()
    }
</script>

@if(Session::has('success'))
<script type="text/javascript">

    Swal.fire({
      //position: 'top-end',
      type: 'success',
      title: 'Success',
      showConfirmButton: false,
      timer: 3000
    })

    window.onload = function(){
        //document.getElementById('print_invoice').click();
    }
</script>
    @php
        Session::forget('success');
    @endphp
@endif

@endsection