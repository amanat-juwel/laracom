@extends('layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SALES RETURN
        
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
                <form class="form" action="{{ url('sales-return') }}" method="post" id="yoyo">
                {{ csrf_field() }}
                <div class="col-md-12">
                    @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}}
                        @php
                        Session::forget('success');
                        @endphp
                    </div>
                    @endif
                    <div class="panel panel-primary">
                        <!-- <div class="panel-heading">
                            Info
                        </div> -->
                        <div class="panel-body" style="padding: 5px">
                            <div class="col-md-2">
                                 Date: 
                                <div class="form-group">
                                    <input type="text" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="date" class=" form-control input-sm" required/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                Customer: 
                                <div class="form-group">
                                    <select name="customer_id" id="customer_id" class="form-control select2"  > 
                                        <option value="">---Select---</option>
                                        @foreach($customers as $data)
                                            <option value="{{ $data->customer_id }}">{{ $data->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7">
                                Note: 
                                <div class="form-group">
                                    <textarea rows="1" name="reason" class="form-control input-sm"></textarea>
                                </div>
                            </div>
                                       
                                
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Cart</div>
                        <div class="panel-body" style="padding: 5px">
                                <table class="table table-striped" style="margin-bottom: 0px">
                                    <thead>
                                        <tr>
                                            <th style="width:3%">#</th>
                                            <th style="width:39%">Item</th>
                                            <th style="width:9%">Qty</th>
                                            <th style="width:14%">Rate</th>
                                            <th style="width:11%">Vat</th>
                                            <th style="width:12%">Amount</th>
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
                                                        <option data-price="{{ $item->mrp }}" value="{{ $item->item_id }}">{{ $item->item_name }} </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="qty form-control input-sm" name="qty[]" value="1" pattern="[0-9]+" title="Digit only" required="">
                                            </td>
                                            <td>
                                                <input type="text" class="price form-control input-sm" name="price[]" id="firstRowPrice" value="" required="">
                                            </td>
                                            <td>
                                                <input type="text" class="dis form-control input-sm" value="0" name="dis[]" >
                                            </td>
                                            <td>
                                                <input type="text" class="amount form-control input-sm" name="amount[]" readonly="">
                                            </td>
                                            <td>
                                                <!-- <input type="button" class="btn btn-danger delete" value="x"> -->
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <th colspan="6">Total:<b class="total">0</b></th>
                                    </tfoot>
                                </table>    
                                <input type="button" class="btn btn-info btn-sm add " id="add_new_item" value="Add New Item">

                        </div>
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Payment</div>
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
                                                <label for="">Discount</label>
                                                <input type="text" name="discount"  id="discount" class="form-control input-sm" value=""/>
                                                <!-- @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif -->
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount Paid*</label>
                                                <input type="text" name="advanced_amount" id="advancePaid"  class="form-control input-sm"  autocomplete="OFF" />
                                                <!-- @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif -->
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Due</label>
                                                <input type="text"  id="dueAmount" name="due" class="form-control input-sm" readonly="" />
                                                <!-- @if($errors->has('item_name'))
                                                    <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                                @endif -->
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Payment Method*</label>
                                                <select name="bank_account_id" id="bank_account_id" class="form-control select2 " >
                                                    <!-- <option value="">--- Select ---</option> -->
                                                    @foreach($accounts as $key => $account)
                                                    <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <br>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success" name="submit" id="submit"><i class="fa fa-shopping-cart" style="font-size:x-large;"></i> Submit</button>
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

<script type="text/javascript">


    function totalAmount(){
        var t = 0;
        $('.amount').each(function(i,e){
            var amt = $(this).val()-0;
            t += amt;
        });
        $('.total').html(t);
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

                    '<td><input type="text" class="qty form-control input-sm" name="qty[]" value="1" pattern="[0-9]+" title="digit only" required></td>' +
                    '<td><input type="text" class="price form-control input-sm" name="price[]" value="" required></td>' +
                    '<td><input type="text" class="dis form-control input-sm" name="dis[]" value="0"></td>' +
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
            //tr.find('.price').val(price);
            
            var qty = tr.find('.qty').val() - 0;
            var dis = tr.find('.dis').val() - 0;
            var price = tr.find('.price').val() - 0;
        
            var total = (qty * price) + ((qty * price * dis)/100);
            tr.find('.amount').val(total);
            totalAmount();
        });
        $('.neworderbody').delegate('.qty ,.price, .dis', 'keyup', function () {
            var tr = $(this).parent().parent();
            var qty = tr.find('.qty').val() - 0;
            var dis = tr.find('.dis').val() - 0;
            var price = tr.find('.price').val() - 0;
        
            var total = (qty * price) + ((qty * price * dis)/100);
            tr.find('.amount').val(total);
            totalAmount();
        });
        
        $('#hideshow').on('click', function(event) {  
             $('#content').removeClass('hidden');
             $('#content').addClass('show'); 
             $('#content').toggle('show');
        });

        
    });
</script>

<script>
function myFunction() {
    
}

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

        });// End of submit

   });

</script> 
<script>
    function Initialize() {
        $('.select2').select2()
    }
</script>

@endsection