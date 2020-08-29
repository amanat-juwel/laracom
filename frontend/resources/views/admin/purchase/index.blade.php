@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        RECEIVE ITEM <a target="" id="" style="color: #fff" class="btn btn-warning btn-sm" href="{{ url('admin/purchase/purchase-details/') }}"><i class="fa fa-list"></i> Receive List</a>
        
        </h1>
    <ol class="breadcrumb">
        
        <li><a target="_blank" style="color: white" class="btn btn-danger btn-sm" href="{{ url('admin/purchase/invoice/'.$lastPurchaseMasterNo) }}"><i class="fa fa-print"></i> Print Last Entry</a></li></li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            
            <!--ADD ROW -->
            <div class="row">
                <form class="form" action="{{ url('admin/purchase/store') }}" method="post" id="yoyo" onsubmit="myFunction()">
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
                            <div class="col-md-3">
                                Date: 
                                            <div class="form-group">
                                                <input type="text" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="purchase_date" id="sales_datememoNo" class=" form-control input-sm" required/>
                                            </div>
                            </div>
                            <div class="col-md-3">
                                Bill No: 
                                            <div class="form-group">
                                                <input autocomplete="off" type="text" id="" value="" name="bill_no" id="" class="form-control input-sm"/>
                                            </div>
                            </div>
                            <div class="col-md-4">
                                Supplier:
                                <div class="form-group"> 
                                                <select name="supplier_id" id="supplier_id" class="form-control select2" > 
                                                    <option value="">--select--</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{ $supplier->supplier_id }}">{{ $supplier->sup_name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <br>
                                <a data-toggle="modal" class="btn btn-info btn-sm" data-target="#myModal"><i class="fa fa-plus"></i> Add New Supplier</a>
                            </div> -->
                            <!-- ADD INSTANT SUPPLIER -->
                                <div id="myModal" class="modal fade" role="dialog">
                                      <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">New Supplier</h4>
                                          </div>
                                          <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px">
                                                        <div class="form-group">
                                                            <label for="">Name</label>
                                                            <input type="text" name="supplier_name" id="supplier_name" class="form-control input-sm" placeholder="Supplier Name"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" style="padding-left: 0px; padding-right: 0px">
                                                        <div class="form-group">
                                                            <label for="">Mobile</label>
                                                            <input type="text" autocomplete="OFF" name="mobile_no" id="mobile_no" class="form-control input-sm" placeholder="ex: 01819363636" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                                          </div>
                                        </div>

                                      </div>
                                    </div>   

                                <!-- //ADD INSTANT SUPPLIER -->
                        </div>
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="panel panel-primary">
                        <div class="panel-heading">Cart</div>
                        <div class="panel-body" style="padding: 5px">
                                <table class="table table-striped" style="margin-bottom: 0px">
                                    <thead>
                                        <tr>
                                            <th style="width:3%">#</th>
                                            <th style="width:37%">Item</th>
                                            <th style="width:20%">Particulars</th>
                                            <th style="width:10%">Qty</th>
                                            <th style="width:12%">Purchase Rate</th>
                                            <th style="width:12%">Amount</th>
                                            <th style="width:6%"><!-- Delete --></th>
                                        </tr>
                                    </thead>
                                    <tbody class="neworderbody">
                                        <tr>
                                            <td class="no">1</td>
                                            <td>
                                                <select name="product_id[]" class="form-control product_id select2" id="firstRowProduct" required="" > 
                                                    <option value="">---Select---</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->item_id }}" >{{ $item->item_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="" name="particulars[]" id="" class="form-control input-sm"/>
                                            </td>

                                            <td>
                                                <input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" required="">
                                            </td>
                                            <td>
                                                <input type="text" autocomplete="OFF" class="price form-control input-sm" name="price[]" id="firstRowPrice" value="" required="">
                                            </td>
                                        
                                            <td>
                                                <input type="text" class="amount form-control input-sm" name="amount[]" readonly="">
                                            </td>
                                            <td>
                                               <!--  <input type="button" class="btn btn-danger delete" value="x"> -->
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <th colspan="6">Total: <b class="total">0</b></th>
                                    </tfoot>
                                </table>    
                                <input type="button" class="btn btn-info input-sm add" value="Add New Item">
                        </div>
                    </div>
                                
                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Checkout</div>
                        <div class="panel-body" style="padding: 5px">
                                <input type="hidden" name="memo_no" id="memoNo" class="memo form-control" value="{{ $lastMemoNo }}" readonly/>

                                        
                                       <!--  <input type="hidden" name="memo_total" id="totalPrice" class="form-control totalPrice input-sm" readonly="" />
                                        <input type="hidden" name="discount"  id="discount" class="form-control input-sm" />
                                        <input type="hidden" name="advanced_amount" id="advancePaid"  class="form-control input-sm" />
                                        <input type="hidden"  id="dueAmount" name="due" class="form-control input-sm" readonly="" /> -->
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Total</label>
                                                <input type="text" name="memo_total" id="totalPrice" class="form-control totalPrice input-sm" readonly="" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Discount</label>
                                                <input type="text" name="discount"  id="discount" class="form-control input-sm" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount Paid*</label>
                                                <input autocomplete="OFF" type="text" name="advanced_amount" id="advancePaid"  class="form-control input-sm" />

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Due</label>
                                                <input type="text"  id="dueAmount" name="due" class="form-control input-sm" readonly="" />

                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Payment Method*</label>
                                                <select name="bank_account_id" id="bank_account_id" class="form-control select2">
                                                    <option value="">--- Select ---</option>
                                                    @foreach($accounts as $key => $account)
                                                    <option value="{{ $account->bank_account_id }}" @if($account->bank_account_id==4) selected @endif>{{ $account->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">                           
                                            <div class="form-group">
                                                <label for=""><i class="fa fa-paperclip"></i> Attachment </label>
                                                <input data-toggle="tooltip" title="MAX LIMIT 2MB" type="file" id="attachment" name="attachment" class="form-control input-sm"/>
                                                @if($errors->has('attachment'))
                                                    <span class="text-danger">{{ $errors->first('attachment')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="">Remarks</label>
                                                <input type="text" name="remarks" id="remarks" class="form-control remarks input-sm" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <br>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success " name="submit" id="submit"><i class="fa fa-check" ></i> Submit</button>
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
        //$('#advancePaid').val(t);
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
                    '<td><input type="text" autocomplete="OFF" class="particulars form-control input-sm" name="particulars[]" value="" ></td>' +
                    '<td><input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" required></td>' +
                    '<td><input type="text" class="price form-control input-sm" autocomplete="OFF" name="price[]" value="" required></td>' +
 
                    '<td><input type="text" class="amount form-control input-sm" name="amount[]" readonly=""></td>' +
                    '<td><input type="button" class="btn btn-danger btn-sm delete" value="x"></td></tr>';
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
            totalAmount();
        });
        $('.neworderbody').delegate('.qty ,.price, .dis', 'keyup', function () {
            var tr = $(this).parent().parent();
            var qty = tr.find('.qty').val() - 0;
            var dis = 0;//tr.find('.dis').val() - 0;
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
                    var tr = '<tr><td class="no">' + n + '</td>' + '<td><select class="form-control product_id select2" name="product_id[]" required>' + product + '</select></td>' +
                        
                        '<td><input type="text" autocomplete="OFF" class="qty form-control input-sm" name="qty[]" value="" required></td>' +
                        '<td><input type="text" class="price form-control input-sm" autocomplete="OFF" name="price[]" value="" required></td>' +
                        '<td><input type="text" class="amount form-control input-sm" name="amount[]" readonly=""></td>' +
                        '<td><input type="button" class="btn btn-danger btn-sm delete" value="x"></td></tr>';
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

<script>
    function Initialize() {
        $('.select2').select2()
    }

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>


@endsection