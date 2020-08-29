@extends('admin.layouts.template')

@section('template')

<style>
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #fff;
    cursor: default;
    background-color: #5cb85c;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
</style>

<section class="content-header">
    <h1>
        MONEY RECEIPT <a target="" id="" style="color: #fff" class="btn btn-info btn-sm" href="{{ url('admin/money-receipt') }}"><i class="fa fa-list"></i> LIST</a>
        
        </h1>
    <ol class="breadcrumb">
        
        <li> <a target="_blank" id="print_money_reeceipt" style="color: white" class="btn btn-danger btn-sm" href="{{ url('admin/money-receipt/print/'.$lastMoneyReceiptId) }}"><i class="fa fa-print"></i> Print Last Money-Receipt</a></li>

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
                </div>
            </div>
            <!--  <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#invoiced"><i class="fa fa-sticky-note"></i> Invoiced</a></li>
                <li class="active"><a data-toggle="tab" href="#un_invoiced"><i class="fa fa-window-restore"></i> Create</a></li>
              </ul> -->

              <div class="tab-content">
                <div id="invoiced" class="tab-pane fade in ">
                  <!----START INVOICED ---->
                   <div class="row">           
                        <form class="form" action="{{ url('admin/money-receipt/store') }}" method="post" id="yoyo">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="panel panel-primary">
           
                                <div class="panel-body" style="padding: 10px">
                                    <div class="col-md-2">
                                                    Date: 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="date" id="" class=" form-control input-sm" required/>
                                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                                    Invoice#: 
                                                    <div class="form-group">
                                                        <select id="sales_master_id" name="sales_master_id" class="form-control input-sm select2">
                                                            <option value="">--Select--</option>
                                                            @foreach($sales as $data)
                                                            <option value="{{$data->sales_master_id}}">{{$globalSettings->invoice_prefix."-BI-".str_pad($data->sales_master_id, 8, '0', STR_PAD_LEFT)}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                                    Customer 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="customer_name" value=""  class="form-control input-sm" readonly="" />
                                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                                    Address: 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="address" value=""  class="form-control input-sm" readonly="" />
                                                    </div>
                                    </div>
                                    <div class="col-md-2">
                                                    Total Due 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="due" name="due"  class="form-control input-sm" readonly="" />
                                                    </div>
                                    </div>  
                                    <div class="col-md-2">
                                                    Amount Receivable 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="amount_receivable" name="amount_receivable"  class="form-control input-sm" readonly="" />
                                                    </div>
                                    </div>                                 
                                                    
                                               
                                        
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading" style="padding: 0">&nbsp Post Sale Discount</div>
                                <div class="panel-body" style="padding: 10px">
                                        
                                    <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input autocomplete="OFF" type="text" name="discount" id="discount" class="form-control input-sm " />
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading" style="padding: 0">&nbsp Payment</div>
                                <div class="panel-body" style="padding: 10px">
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Narration</label>
                                                <select name="narration" id="narration" class="form-control input-sm " >
                                                    <option value="">--- Select ---</option>
                                                    <option value="ADVANCE">ADVANCE</option>
                                                    <option value="RE-ADVANCE">RE-ADVANCE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Payment Method*</label>
                                                <select name="bank_account_id" id="bank_account_id" class="form-control input-sm " >
                                                    <option value="">--- Select ---</option>
                                                    @foreach($accounts as $key => $account)
                                                    <option value="{{ $account->bank_account_id }}">{{ $account->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <input type="text" autocomplete="OFF" name="payment_by" value=""  class="form-control input-sm" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input autocomplete="OFF" type="text" name="amount" id="amount" class="form-control input-sm " />
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <br>
                                            <input type="hidden" name="type" id="" value="invoiced"/>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success pull-right" name="submit" id="submit"><i class="fa fa-shopping-cart" style="font-size:x-large;"></i> Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>                              
                  <!-- END INVOICED ---->
                </div>
                <div id="un_invoiced" class="tab-pane fade in active">
                  <!----START UN-INVOICED ---->
                   <div class="row">           
                        <form class="form" action="{{ url('admin/money-receipt/store') }}" method="post" id="un-invoiced-form">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="panel panel-primary">
           
                                <div class="panel-body" style="padding: 10px">
                                    <div class="col-md-2">
                                                    Date: 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="datepicker2" value="@php echo date('Y-m-d'); @endphp" name="date" id="" class=" form-control input-sm" required/>
                                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                                    Customer 
                                                    <div class="form-group">
                                                        <select name="customer_id" id="customer_id" class="form-control input-sm select2"  style="width: 100%;"> 
                                                            <option value=''>---Select---</option>
                                                            @foreach($customers as $customer)
                                                                <option value="{{ $customer->customer_id }}">{{ $globalSettings->invoice_prefix."-".str_pad($customer->customer_code, 8, '0', STR_PAD_LEFT) }} | {{$customer->customer_name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                    </div>
                                    <div class="col-md-2">
                                                    Total Due 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="due_un_invoiced" name="due"  class="form-control input-sm" readonly="" />
                                                    </div>
                                    </div>  
                                    <div class="col-md-2">
                                                    Amount Receivable 
                                                    <div class="form-group">
                                                        <input type="text" autocomplete="OFF" id="amount_receivable_un_invoiced" name="amount_receivable"  class="form-control input-sm" readonly="" />
                                                    </div>
                                    </div>                                 
                                                    
                                               
                                        
                                </div>
                            </div>
                        </div>
                        
                       <!--  <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading" style="padding: 0">&nbsp Post Sale Discount</div>
                                <div class="panel-body" style="padding: 10px">
                                        
                                    <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input autocomplete="OFF" type="text" name="discount" id="discount_un_invoiced" class="form-control input-sm " />
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div> -->
                        
                        <div class="col-md-12">
                            <div class="panel panel-success">
                                <div class="panel-heading" style="padding: 0">&nbsp Payment</div>
                                <div class="panel-body" style="padding: 10px">
                                        
                                      <!--   <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Narration</label>
                                                <select name="narration" id="narration" class="form-control input-sm " >
                                                    <option value="REST-AMOUNT">REST-AMOUNT</option>
                                                    <option value="ADVANCE">ADVANCE</option>
                                                    <option value="RE-ADVANCE">RE-ADVANCE</option>
                                                </select>
                                            </div>
                                        </div> -->
                                        
                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label for="">Description </label>
                                                <input type="text" autocomplete="OFF" name="payment_by" value=""  class="form-control input-sm" />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Amount</label>
                                                <input autocomplete="OFF" type="text" name="amount" id="amount" class="form-control input-sm " />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
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
                                        
                                        <div class="col-md-12">
                                            <br>
                                            <input type="hidden" name="type" id="" value="un_invoiced"/>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success pull-right" name="submit" id="submit" value="Submit">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>                              
                  <!-- END UN-INVOICED ---->
                  
                </div>
              </div>
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

<!--START SCRIPT FOR INVOICED M.R -->

<script type="text/javascript">

$(document).ready(function () {

   $("#sales_master_id").change(function() {
    
        var sales_master_id = $(this).val();
        $.ajax({
                url:'{{url('admin/money-receipt/sales-info/')}}'+"/"+sales_master_id+"/",
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $("#customer_name").val(data.customer_name);
                    $("#address").val(data.address);
                    $("#due").val(data.amount_receivable);
                    $("#amount_receivable").val(data.amount_receivable);
                    console.log(data);
                },
                error: function (data) {
                    $('#Error').text('Error occured.');
                }
            });

    });

});
   
</script>

<script>
$(document).ready(function () {
   $("#discount").keyup(function() {
        var due = parseFloat($('#due').val());
        var discount = parseFloat($(this).val());

        $('#amount_receivable').val(due-discount);
    });

});
</script> 

<!--END SCRIPT FOR INVOICED M.R -->


<!--START SCRIPT FOR UN-INVOICED M.R -->
<script type="text/javascript">
$(document).ready(function () {
   $("#customer_id").change(function() {
        var customer_id = $(this).val();
        $.ajax({
            url:'{{url('admin/money-receipt/customer-balance-info/')}}'+"/"+customer_id+"/",
            type:"GET",
            dataType:"json",
            success:function(data) {
                $("#due_un_invoiced").val(data.amount_receivable);
                $("#amount_receivable_un_invoiced").val(data.amount_receivable);
                console.log(data);
            },
            error: function (data) {
                $('#Error').text('Error occured.');
            }
        });
    });
});
</script>
<script>
$(document).ready(function () {
   $("#discount_un_invoiced").keyup(function() {
        var due = parseFloat($('#due_un_invoiced').val());
        var discount = parseFloat($(this).val());

        $('#amount_receivable_un_invoiced').val(due-discount);
    });

});
</script> 
<!--END SCRIPT FOR UN-INVOICED M.R -->
@if(Session::has('success'))
<script type="text/javascript">
    window.onload = function(){
        document.getElementById('print_money_reeceipt').click();
    }
</script>
    @php
        Session::forget('success');
    @endphp
@endif

@endsection