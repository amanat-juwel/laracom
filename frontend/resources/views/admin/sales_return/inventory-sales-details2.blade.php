@extends('layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Inventory Sales Details
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Inventory Sales Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"> Sales Details</h3>
                
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                        @foreach($salesMaster as $key => $salesMasters)

                        <form class="form" action="{{ url('/sales/memo_details/'.$salesMasters->sales_master_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                        
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Memo No</label>
                                        <input type="text" value="{{ $salesMasters->memo_no }}" name="memo_no" id="memoNo" class="form-control" readonly/>
                                        <input type="hidden" value="{{ $salesMasters->sales_master_id }}" name="sales_master_id"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Customer Name</label>
                                        <input type="text" value="{{ $salesMasters->customer_name }}" name="customer_name" id="" class="form-control" readonly/>
                                        <input type="hidden" value="{{ $salesMasters->customer_id }}" name="customer_id"/>
                                    </div>
                                </div>
                                @if($due!=0)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tran. Date</label>
                                        <input type="date" name="updated_at" value="{{ date('Y-m-d') }}" class="form-control" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Total Payable</label>
                                        <input type="text" value="{{ $due }}" name="payable"  id="total" class="form-control" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Due</label>
                                        <input type="text" value="{{ $due }}" name="due"  id="due" class="form-control" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Discount</label>
                                        <input type="text" name="discount" value="0"  id="discount" class="form-control" onkeyup="calculate()" onchange="calculate()"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Paid Amount</label>
                                        <input type="text" name="paid"  id="paid" class="form-control" onkeyup="calculate()" onchange="calculate()" required/>
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
                                
                                <div class="col-md-2 pay-button">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger" name="" id="" ><i class="fa fa-money" ></i> Receive Cash</button>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-2">
                                    <div class="form-group">
                                         <label for="">Status</label><br>
                                        <button type="submit" class="btn btn-success" ><i class="fa fa-money" ></i> PAID</button>
                                    </div>
                                </div>
                                @endif
                            </div>                            
                            <br>
                            @include('sales.inventory-modal')
                            @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div class="portlet box green">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <!--
                                                    <a href="/InventorySalesDetails/Add/23" id="Add" class="btn btn-default editDialog"><i class="glyphicon glyphicon-plus"></i> Add Item To Memo</a>
                                                    -->
                                                     Memo Details
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered" id="inventory_sales_details">
                                            <thead>
                                                <tr>
                                                    <th>Sl No.</th>
                                                    <th>Item Name</th>
                                                    <th>Stock Location Name</th>
                                                    <th>Imei</th>
                                                    <th>Quantity</th>
                                                    <th>Sales Price</th>
                                                    <th>Item Vat</th>
                                                  <!--  <th>Return Item</th>  -->
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($salesDetail as $key => $salesDetails)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $salesDetails->item_name }}</td>
                                                    <td>{{ $salesDetails->stock_location_name }}</td>
                                                    <td>{{ $salesDetails->imei }}</td>
                                                    <td>{{ $salesDetails->quantity }}</td>
                                                    <td>{{ $salesDetails->sales_price }}</td>
                                                    <td>{{ $salesDetails->item_vat }}</td>
                                                 <!--   <td><a href="#" class="btn btn-primary"><i class="fa fa-reply"></i> Return</a></td>-->
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
</section>
<!-- End Main Content -->


<script>
    $(document).ready(function () {
        var total = Number($('#total').val());
        if (total <= 0) {
            $('#cmd').prop("disabled", true);
        }
    });

    $('.editDialog').click(function (event) {
        event.preventDefault();
        $.get(this.href, function (response) {
            $('.divForUpdate').html(response);
        });
        $('#inventory_new_item_add').modal({
            backdrop: 'static',
        }, 'show');
    });

    $('.btn-sm').click(function (event) {
        event.preventDefault();
        $.get(this.href, function (response) {
            $('.divForUpdate').html(response);
            $('.modal-title').html("Return Product");
        });
        $('#inventory_new_item_add').modal({
            backdrop: 'static',
        }, 'show');
    });

    function calculate() {
        var total = Number($('#total').val());
        var discount = Number($('#discount').val());
        var paid = Number($('#paid').val());
        var due = total - discount - paid;
        $('#due').val(due);

    }

    // function printStatement() {
    //     var id = '23';
    //     //window.location = "/InventoryPurchaseDetails/ExportMemo/?id=" + id;
    //     $.ajax({
    //         type: 'POST',
    //         url: '/InventoryPurchaseDetails/ExportMemo',
    //         data: { id: id },
    //         success: function (data) {
    //             var str = "data:application/pdf;base64," + data;
    //             var object = '<iframe width="100%" height="100%" src="' + str + '"></iframe>';
    //             var generator = window.open("", "", "top=100,left=500,width=500,height=500");
    //             generator.document.write(object);
    //             generator.document.close();
    //         }
    //     });
    // }

    // $('#itemAddForm').validate({
    //     rules: {
    //         Transaction_Date: {
    //             required: true
    //         },
    //         paid: {
    //             number: true
    //         },
    //         discount: {
    //             number: true
    //         },
    //         Particulars: {
    //             required: true
    //         },
    //         due: {
    //             number: true,
    //             min: 0
    //         }
    //     },
    //     messages: {
    //         Transaction_Date: {
    //             required: "Provide Transaction Date"
    //         },
    //         paid: {
    //             number: "Paid Amount must be a number"
    //         },
    //         discount: {
    //             number: "Discount must be a number"
    //         },
    //         Particulars: {
    //             required: "Provide Particular"
    //         },
    //         due: {
    //             number: "must be number",
    //             min: "Due can't be negetive"
    //         }
    //     }
    // });

</script>
@endsection