@extends('admin.layouts.template')


@section('template')
<style type="text/css" media="print">
    @page 
    {
        size: A4 landscape;
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
    @media print {
      #noPrint {
        display: none;
      }
      .noPrint {
        display: none;
      }
    }
</style>

<!-- Content Header -->
<section class="content-header">
    <h1>
        RETURN & EXCHANGE
        <a target="" style="color: white;" class="btn btn-info btn-sm" href="{{ url('admin/sales-return/exchange/') }}"><i class="fa fa-list"></i> List</a>
        </h1>
    <ol class="breadcrumb">

    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            
            <!--ADD ROW -->
            <div class="row">
                <form class="form" action="{{ url('admin/sales-return/exchange') }}" method="post" name="yoyo" id="yoyo" onsubmit="myFunction()">
                {{ csrf_field() }}
                <div class="col-md-12">
                    @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}}
                       
                    </div>
                    @endif
                    <div class="panel panel-primary" id="noPrint">
                        <div class="panel-body" style="padding: 5px">
                            <div class="col-md-2">
                                Date *: 
                                <div class="form-group">
                                    <input autocomplete="OFF" type="text" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="date" id="date" class=" form-control input-sm" required/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                Sales Invoice no:
                                <div class="form-group">
                                    <input data-toggle="tooltip" title="" type="text" name="sales_master_id" id="sales_master_id" value="" class=" form-control input-sm"  autocomplete="OFF" />
                                </div>
                            </div>  
                            <div class="col-md-4">
                                Customer *: 
                                <select name="customer_id" id="customer_id" class="form-control select2"  > 
                                    <option value="">---Select---</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}-{{$customer->mobile_no}} </option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="col-md-4">
                                <br>
                                <a target="_blank" id="print_invoice" style="color: white" class="btn btn-danger btn-sm pull-right" href="{{ url('/sales/return/exchange/'.$lastReturnExcMaster.'/print')  }}"><i class="fa fa-print"></i> Print Last Entry</a>
                            </div> 
                            
                        </div> 
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading" style="padding: 0">&nbsp Return From Customer</div>
                        <div class="panel-body" style="padding: 5px;margin-top: 5px;">
                                <table class="table table-striped" style="margin-bottom: 0px">
                                    <thead>
                                        <tr>
                                            <th style="width:3%">No</th>
                                            <th style="width:44%">Item</th>
                                            <th style="width:15%">Qty</th>
                                            <th style="width:16%">Unit Price</th>
                                            <th style="width:16%">Amount</th>
                                            <th style="width:6%"><!-- Delete --></th>
                                        </tr>
                                    </thead>
                                    <tbody class="neworderbody">
                                        <tr>
                                            <td class="no">1</td>
                                            <td>
                                                <select name="product_id[]" class="form-control product_id select2" id="firstRowProduct" required="" > 
                                                    <option value="">---Select---</option>
                                                    @foreach($sold_items as $item)
                                                        <option value="{{ $item->item_id }}" data-price="@if($item->discounted_price==null){{ $item->mrp }}@else{{ $item->discounted_price }}@endif">{{ $item->item_code }} | {{ $item->item_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" pattern="[0-9]+" title="digit only" required="">
                                            </td>
                                            <td>
                                                <input type="text" autocomplete="OFF" class="price form-control input-sm"  name="price[]" id="firstRowPrice" value="" required="">
                                            </td>
                                            <td>
                                                <input type="text" class="amount form-control input-sm" name="amount[]" readonly="">
                                            </td>
                                            <td>
                                                <input type="button" class="btn btn-danger btn-xs delete noPrint" value="x">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="6">Total Qty:<b class="total_qty">0</b> | Total Amount:<b class="total">0</b></td>
                                    </tfoot>
                                </table> 
                                <div id="noPrint">   
                                    <input type="button" class="btn btn-primary btn-sm add" value="Add New Item">
                                </div>
                        </div>
                    </div>

                                
                </div>

               <!-- OUT SECTION-->
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0">&nbsp New Sale to Customer</div>
                        <div class="panel-body" style="padding: 5px;margin-top: 5px;">
                            <table class="table table-striped" style="margin-bottom: 0px">
                                <thead>
                                    <tr>
                                        <th style="width:3%">No</th>
                                        <th style="width:44%">Item</th>
                                        <th style="width:15%">Qty</th>
                                        <th style="width:16%">Unit Price</th>
                                        <th style="width:16%">Amount</th>
                                        <th style="width:6%">
                                            <!-- Delete -->
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="outorderbody">
                                    <tr>
                                        <td class="no">1</td>
                                        <td>
                                            <select name="out_product_id[]" class="form-control product_id_out select2" id="firstRowProduct" > 
                                                <option value="">---Select---</option>
                                                @foreach($new_sales_items as $item)
                                                  @if($item->stock_in-$item->stock_out>0)
                                                    <option value="{{ $item->item_id }}" data-price="@if($item->discounted_price==null){{ $item->mrp }}@else{{ $item->discounted_price }}@endif" data-availability="{{$item->stock_in-$item->stock_out}}">{{ $item->item_code }} | {{ $item->item_name }} | Stock: {{$item->stock_in-$item->stock_out}}
                                                    </option>
                                                  @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" autocomplete="OFF" class="out-qty form-control input-sm" name="out_qty[]" value="" pattern="[0-9]+" title="digit only" >
                                        </td>
                                        <td>
                                            <input type="text" autocomplete="OFF" class="out-price form-control input-sm" name="out_price[]" id="firstRowPriceOut" value="" >
                                        </td>
                                        <td>
                                            <input type="text" class="out-amount form-control input-sm" name="out_amount[]" readonly="">
                                        </td>
                                        <td>
                                            <input type="button" class="btn btn-danger btn-xs delete noPrintOut" value="x">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <td colspan="6">Total Qty:<b class="total_qty_out">0</b> | Total Amount:<b class="out-total">0</b></td>
                                </tfoot>
                            </table>
                            <div id="noPrint">
                                <input type="button" class="btn btn-primary btn-sm add-out" value="Add New Item">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- OUT SECTION-->
                 <!-- FINAL SECTION-->
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-body" style="padding: 5px;margin-top: 5px;">
                            <table class="table table-striped" style="margin-bottom: 0px">
                                <thead>
                                    <tr>
                                        <th style="width:33%;" class="text-green">Cash In</th>
                                        <th style="width:33%" class="text-red">Cash Return</th>
                                        <th style="width:33%" class="">Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody class="inoutbody">
                                    <tr>                                   
                                        <td>
                                            <input autocomplete="OFF" type="number"  class="inward form-control input-sm" name="inward"  pattern="[0-9]+" title="digit only" required="">
                                        </td>
                                        <td>
                                            <input autocomplete="OFF" type="number" class="outward form-control input-sm" name="outward"  required="">
                                        </td>
                                        <td>
                                            <select name="bank_account_id" id="bank_account_id" class="form-control select2 " >
                                                <option value="">--- Select ---</option>
                                                @foreach($accounts as $key => $account)
                                                <option value="{{ $account->bank_account_id }}" @if($account->bank_account_id == 4) selected @endif>{{ $account->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                    </tr>
                                </tbody>
                                
                            </table>
                            
                        </div>
                    </div>
                </div>
                <!-- FINAL SECTION-->
                <div class="col-md-12"  id="noPrint">
                    <div class="panel panel-primary">
                        
                        <div class="panel-body" style="padding: 5px;margin-top: 5px">
                                

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="memo_total" id="totalPrice" class="form-control totalPrice input-sm" readonly="" /> 

                                                <input type="hidden" name="memo_total" id="totalPriceOut" class="form-control totalPriceOut input-sm" readonly="" />

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="discount"  id="discount" class="form-control input-sm"/>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden" name="advanced_amount" id="advancePaid"  class="form-control input-sm" />

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="hidden"  id="dueAmount" name="due" class="form-control input-sm" readonly="" />
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <a type="" class="preview btn btn-info" name="" id=""><i class="fa fa-eye" style=""></i> Preview</a>
                                            </div>
                                        </div>
                                        <div class="col-md-2">                           
                                            <div class="form-group">
                                             
                                            </div> 
                                        </div>
                                        <div class="col-md-2">
                                            
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success" name="submit" id="submit"><i class="fa fa-shopping-cart" style=""></i> Save</button>
                                            </div>
                                        </div>
                       
                                <hr>
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

<script type="text/javascript">
    $('form').submit(function() {
      $(this).find("input[type='submit']").prop('disabled',true);
    });
</script>

<script type="text/javascript">
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
        $('.total').html(t);
        $('.total_qty').html(t_qty);
        $('#totalPrice').val(t);
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
            var memo_total = parseFloat($('#totalPrice').val());
            var discount = parseFloat($('#discount').val());
            if(isNaN(discount)){
                discount = 0;
            }
            var advancePaid = parseFloat($('#advancePaid').val());
            var due = memo_total - discount - advancePaid;
            $('#dueAmount').val(due);
        });
        $('#discount').keyup(function(){
            var advancePaid = parseFloat($('#advancePaid').val());
            var memo_total = parseFloat($('#totalPrice').val());
            var discount = parseFloat($('#discount').val());
            if(isNaN(advancePaid)){
                advancePaid = 0;
            }
            //alert(advancePaid);
            var due = memo_total - discount - advancePaid;
            $('#dueAmount').val(due);
        });
        $('.add').click(function () {
            if($('#firstRowProduct').val() == ''){
                alert('Select an item first');
            }

            else if($('#totalPrice').val() > 0){

                var product = $('.product_id').html();

                var n = ($('.neworderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' + '<td><select class="form-control product_id select2" name="product_id[]" required>' + product + '</select></td>' +
                    '<td><input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" pattern="[0-9]+" title="Digit only" required></td>' +
                    '<td><input type="text" autocomplete="OFF" class="price form-control input-sm" name="price[]" value="" required></td>' +
                    '<td><input type="text" class="amount form-control input-sm" name="amount[]" readonly=""></td>' +
                    '<td><input type="button" class="btn btn-danger btn-xs delete noPrint" value="x"></td></tr>';
                $('.neworderbody').append(tr);
                Initialize(); // to initialize select2 again
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
            
            var qty = tr.find('.qty').val() - 0;
            var dis = 0;//tr.find('.dis').val() - 0;
            var price = tr.find('.price').val() - 0;
        
            var total = (qty * price) + ((qty * price * dis)/100);
            tr.find('.amount').val(total);
            //To focus on qty after item is selected
            setTimeout(function() { $('.qty').focus() }, 100);

            //$(this).attr('disabled', 'disabled');
            //$('.select2-selection__choice').css({'color':'black'});
            //$('#firstRowProduct').attr('disabled');

            totalAmount();
        });
        $('.neworderbody').delegate('.qty ,.price, .dis', 'keyup', function () {
            var tr = $(this).parent().parent();
            var qty = tr.find('.qty').val() - 0;
            var dis = 0;//tr.find('.dis').val() - 0;
            var price = tr.find('.price').val() - 0;
        
            var total = (qty * price) - ((qty * price * dis)/100);
            tr.find('.amount').val(total);
            totalAmount();
            inwardOutward();

        });
        
        $('#hideshow').on('click', function(event) {  
             $('#content').removeClass('hidden');
             $('#content').addClass('show'); 
             $('#content').toggle('show');
        });

        $('.preview').on('click', function(event) {  
             window.print();
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

                var n = ($('.neworderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' + '<td><select multiple="multiple"  class="form-control product_id select2" name="product_id[]" required>' + product + '</select></td>' +
                    '<td><input type="text" class="qty form-control input-sm" name="qty[]" value="" pattern="[0-9]+" title="Digit only" required></td>' +
                    '<td><input type="text" class="price form-control input-sm" name="price[]" value="" required></td>' +
                    '<td><input type="text" class="amount form-control input-sm" name="amount[]" readonly=""></td>' +
                    '<td><input type="button" class="btn btn-danger btn-xs delete" value="x"></td></tr>';
                $('.neworderbody').append(tr);
                Initialize(); // to initialize select2 again
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

    


</script>
<!-- START OUT SECTION -->

<script type="text/javascript">
    function totalAmountOut(){
        var t = 0;
        var t_qty = 0;
        $('.out-amount').each(function(i,e){
            var amt = $(this).val()-0;
            t += amt;
        });
        $('.out-qty').each(function(i,e){
            var qty = $(this).val()-0;
            t_qty += qty;
        });
        
        $('.out-total').html(t);
        $('.total_qty_out').html(t_qty);
        $('#totalPriceOut').val(t);
        $('#dueAmount').val(t);
    }
    function inwardOutward(){
    
        var t_in = 0;
        $('.amount').each(function(i,e){
            var in_amt = $(this).val()-0;
            t_in += in_amt;
        });
        var t_out= 0;
        $('.out-amount').each(function(i,e){
            var out_amt = $(this).val()-0;
            t_out += out_amt;
        });
      if(t_in > t_out){
        var res =t_in-t_out;
        $('.inward').val(0);
        $('.outward').val(res);
      }else if (t_in < t_out){
        var res =t_out-t_in;
        $('.inward').val(res);
        $('.outward').val(0);
      }else{
        $('.inward').val(0);
        $('.outward').val(0);
      }
        
    }
    $(function () {
        $('.getmoney').change(function(){
            var total = $('.total').html();
            var getmoney = $(this).val();
            var t = getmoney - total;
            $('.backmoney').val(t).toFixed(2);
        });
        $('#advancePaid').keyup(function(){
            var memo_total = parseFloat($('#totalPrice').val());
            var discount = parseFloat($('#discount').val());
            if(isNaN(discount)){
                discount = 0;
            }
            var advancePaid = parseFloat($('#advancePaid').val());
            var due = memo_total - discount - advancePaid;
            $('#dueAmount').val(due);
        });
        $('#discount').keyup(function(){
            var advancePaid = parseFloat($('#advancePaid').val());
            var memo_total = parseFloat($('#totalPrice').val());
            var discount = parseFloat($('#discount').val());
            if(isNaN(advancePaid)){
                advancePaid = 0;
            }
            //alert(advancePaid);
            var due = memo_total - discount - advancePaid;
            $('#dueAmount').val(due);
        });
        $('.add-out').click(function () {
            if($('#firstRowProductOut').val() == ''){
                alert('Select an item first');
            }

            else if($('#totalPriceOut').val() > 0){

                var product = $('.product_id_out').html();

                var n = ($('.outorderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' + '<td><select class="form-control product_id_out select2" name="out_product_id[]" required>' + product + '</select></td>' +
                    '<td><input type="text" autocomplete="OFF" class="out-qty form-control input-sm" name="out_qty[]" value="" pattern="[0-9]+" title="Digit only" required></td>' +
                    '<td><input type="text" autocomplete="OFF" class="out-price form-control input-sm" name="out_price[]" value="" required></td>' +
                    '<td><input type="text" class="out-amount form-control input-sm" name="out_amount[]" readonly=""></td>' +
                    '<td><input type="button" class="btn btn-danger btn-xs delete noPrint" value="x"></td></tr>';
                $('.outorderbody').append(tr);
                Initialize(); // to initialize select2 again
            }
            else{
                $('#firstRowPriceOut').focus();
            }
        });
        $('.outorderbody').delegate('.delete', 'click', function () {
            $(this).parent().parent().remove();
            totalAmountOut();
            inwardOutward();
        });
        $('.outorderbody').delegate('.product_id_out', 'change', function () {
            var tr = $(this).parent().parent();
            var price = tr.find('.product_id_out option:selected').attr('data-price');
            var availableQty = tr.find('.product_id_out option:selected').attr('data-availability');
            tr.find('.out-price').val(price);
            
            var qty = tr.find('.out-qty').val() - 0;
            var dis = 0;//tr.find('.dis').val() - 0;
            var price = tr.find('.out-price').val() - 0;
        
            var total = (qty * price) + ((qty * price * dis)/100);
            tr.find('.out-total').val(total);
            console.log(total);
            //To focus on qty after item is selected
            setTimeout(function() { $('.out-qty').focus() }, 100);

            //$(this).attr('disabled', 'disabled');
            //$('.select2-selection__choice').css({'color':'black'});
            //$('#firstRowProduct').attr('disabled');

            totalAmountOut();
            inwardOutward();
        });
        $('.outorderbody').delegate('.out-qty ,.out-price, .out-dis', 'keyup', function () {
            var tr = $(this).parent().parent();
            var qty = tr.find('.out-qty').val() - 0;
            var dis = 0;//tr.find('.dis').val() - 0;
            var price = tr.find('.out-price').val() - 0;
            
            var availableQty = tr.find('.product_id_out option:selected').attr('data-availability');
            if(qty>availableQty){      
                alert('Stock not available!')     
                tr.find('.out-qty').val('');
            }

            var total = (qty * price) - ((qty * price * dis)/100);
            tr.find('.out-amount').val(total);
            totalAmountOut();
            inwardOutward();

        });
        
        $('#hideshow').on('click', function(event) {  
             $('#content').removeClass('hidden');
             $('#content').addClass('show'); 
             $('#content').toggle('show');
        });

        $('.preview').on('click', function(event) {  
             window.print();
        });

        
    });

    $(document).keypress(
        function(event){
         if (event.which == '13') {
            //ADDING NEW ROW ON ENTER CLICK
            if($('#firstRowProductOut').val() == ''){
                alert('Select an item first');
            }

            else if($('#totalPriceOut').val() > 0){

                var product = $('.product_id_out').html();

                var n = ($('.outorderbody tr').length - 0) + 1;
                var tr = '<tr><td class="no">' + n + '</td>' + '<td><select multiple="multiple"  class="form-control product_id_out select2" name="out_product_id[]" required>' + product + '</select></td>' +
                    '<td><input type="text" class="out-qty form-control input-sm" name="out_qty[]" value="" pattern="[0-9]+" title="Digit only" required></td>' +
                    '<td><input type="text" class="out-price form-control input-sm" name="out_price[]" value="" required></td>' +
                    '<td><input type="text" class="out-amount form-control input-sm" name="out_amount[]" readonly=""></td>' +
                    '<td><input type="button" class="btn btn-danger btn-xs delete" value="x"></td></tr>';
                $('.outorderbody').append(tr);
                Initialize(); // to initialize select2 again
            }
            else{
                $('#firstRowPriceOut').focus();
            }
            //ADDING NEW ROW ON ENTER CLICK
            setTimeout(function() { $('.product_id_out').focus();}, 100);
            //setTimeout(function() { $('.product_id').trigger("click");}, 100);
            event.preventDefault();
          }


    });

    
</script>
<!-- END OUT SECTION -->
<script type="text/javascript">
    
</script>
<script>
 $(document).ready(function () {
       
       $("#mobile_no").keyup(function() {
            if($("#mobile_no").val()=='8'){
                $("#mobile_no").val('');
            }
       });
    });

$(document).ready(function () {
    
   $("#submit").click(function() {
        var due = $('#dueAmount').val();
        var advancePaid = parseInt($('#advancePaid').val());
        var bank_account_id = parseInt($('#bank_account_id').val());
        var mobile_no = document.getElementById("mobile_no").value;
        var supplier_name = document.getElementById("supplier_name").value;
        var supplier_id = document.getElementById("supplier_id").value;

        //$('select').attr("disabled", false);

        if(due<0 || isNaN(due)){
            alert("Wrong amount input");
            $('#advancePaid').focus();
            event.preventDefault();
        }
        else if(advancePaid>0 && isNaN(bank_account_id)){
            alert("Plaese select payment method");
            event.preventDefault();
        }
        else if(mobile_no!='' && supplier_name ==''){
            alert("Supplier name missing!");
            event.preventDefault();
        }

        else if(mobile_no=='' && supplier_name =='' && supplier_id == ''){
            alert("Select a Supplier!");
            event.preventDefault();
        }
    });// End of submit

});
</script>
<style type="text/css">
    .select2-selection__choice{
        color: black !important;
    }
</style>
<script>
    function Initialize() {
        $('.select2').select2()
    }

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });

    $("#memo_no").change(function() {
        var memo_no = $("#memo_no").val();
        var from = $("#from").val();
        $.ajax({
                url:'{{url('/purchase/varify/memo_no/')}}'+"/"+memo_no,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $("#loader").show();
                },
                success:function(data) {
                    if(data.validity == 'false'){
                        $("#memo_no").val('');
                        alert('Invoice already Exist');
                    }
                    console.log(data);
                },
                complete: function(){
                    $("#loader").hide();
                },
                error: function (data) {
                    $('#Error').text('Error occured.');
                }
            });

    });

</script>


@if(Session::has('success'))
<script type="text/javascript">
    window.onload = function(){
        //document.getElementById('print_invoice').click();
    }
</script>
    @php
        Session::forget('success');
    @endphp
@endif

@endsection