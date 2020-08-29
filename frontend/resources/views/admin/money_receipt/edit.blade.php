@extends('admin.layouts.template')
@section('template')

<section class="content-header">
    <h1>
        EDIT MONEY RECEIPT
        
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
                <form class="form" action="{{ url('admin/money-receipt/store') }}" method="post" id="yoyo">
                {{ csrf_field() }}
                <div class="col-md-12">
                    @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}}
                       
                    </div>
                    @endif
                    <div class="panel panel-primary">
                        <!-- <div class="panel-heading">
                            Info
                        </div> -->
                        <div class="panel-body" style="padding: 10px">
                            <div class="col-md-2">
                                            Date: 
                                            <div class="form-group">
                                                <input type="text" autocomplete="OFF" id="datepicker" value="@php echo date('Y-m-d'); @endphp" name="date" value="{{ $money_receipt->date }}" class=" form-control input-sm" required/>
                                            </div>
                            </div>
                            <div class="col-md-3">
                                            Invoice#: 
                                            <div class="form-group">
                                                <select id="sales_master_id" name="sales_master_id" class="form-control input-sm select2">
                                                    <option value="">--Select--</option>
                                                    @foreach($sales as $data)
                                                    <option value="{{$data->sales_master_id}}" @if($money_receipt->sales_master_id == $data->sales_master_id) selected @endif>{{"JMG-BI-".str_pad($data->sales_master_id, 8, '0', STR_PAD_LEFT)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                            </div>
                            <div class="col-md-3">
                                            Customer 
                                            <div class="form-group">
                                                <input type="text" autocomplete="OFF" id="customer_name" value="{{ $money_receipt->customer_name }}"  class="form-control input-sm" readonly="" />
                                            </div>
                            </div>
                            <div class="col-md-4">
                                            Address: 
                                            <div class="form-group">
                                                <input type="text" autocomplete="OFF" id="address" value="{{ $money_receipt->address }}"  class="form-control input-sm" readonly="" />
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
                    <div class="panel panel-success">
                        <div class="panel-heading" style="padding: 0">&nbsp Payment</div>
                        <div class="panel-body" style="padding: 10px">
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Narration</label>
                                        <select name="narration" id="narration" class="form-control input-sm " >
                                            <option value="">--- Select ---</option>
                                            <option value="ADVANCE" @if($money_receipt->narration=="ADVANCE") selected @endif>ADVANCE</option>
                                            <option value="RE-ADVANCE" @if($money_receipt->narration=="RE-ADVANCE") selected @endif>RE-ADVANCE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Payment Method*</label>
                                        <select name="bank_account_id" id="bank_account_id" class="form-control input-sm " >
                                            <option value="">--- Select ---</option>
                                            @foreach($accounts as $key => $account)
                                            <option value="{{ $account->bank_account_id }} @if($money_receipt->bank_account_id == $account->bank_account_id) selected @endif">{{ $account->bank_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    
                                    <div class="form-group">
                                        <label for="">Payment By </label>
                                        <input type="text" autocomplete="OFF" name="payment_by" value="{{$money_receipt->payment_by}}"  class="form-control input-sm" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Amount</label>
                                        <input autocomplete="OFF" type="text" name="amount" id="amount" class="form-control input-sm" value="{{$money_receipt->amount}}" />
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <br>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success pull-right" name="submit" id="submit"><i class="fa fa-shopping-cart" style="font-size:x-large;"></i> Submit</button>
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
    window.onload = function(){
        document.getElementById('print_money_reeceipt').click();
    }
</script>
    @php
        Session::forget('success');
    @endphp
@endif

@endsection