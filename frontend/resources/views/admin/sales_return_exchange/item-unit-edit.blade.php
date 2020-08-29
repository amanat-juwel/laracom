@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit : Sold Item Details
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit : Sold Item Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-5">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                       
                            <form class="form" name="editForm" action="{{ url('admin/sales/invoice/unit/'.$sales_master_id)}}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="sales_master_id" value="{{ $sales_master_id }}">
                                <input type="hidden" name="sales_details_id" value="{{ $itemUnitById->sales_details_id }}">
                                <input type="hidden" name="item_id_old" value="{{ $itemUnitById->item_id }}">
                                <input type="hidden" name="sales_price_old" value="{{ $itemUnitById->sales_price }}">
                                <input type="hidden" name="quantity_old" value="{{ $itemUnitById->quantity }}">

                                <div class="form-group">
                                    <label for="">Item Name</label>
                                    <select name="item_id" id="item_id" class="form-control select2"  required> 
                                        <option value="">---Select---</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Quantity</label>
                                    <input type="text" name="quantity" value="{{ $itemUnitById->quantity }}" class="form-control" required/>
                                    @if($errors->has('quantity'))
                                        <span class="text-danger">{{ $errors->first('quantity')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Unit Price</label>
                                    <input type="text" name="sales_price" value="{{ $itemUnitById->sales_price }}" class="form-control" required/>
                                    @if($errors->has('sales_price'))
                                        <span class="text-danger">{{ $errors->first('sales_price')}}</span>
                                    @endif
                                </div>
                                <!-- <div class="form-group">
                                    <label for="">Vat %</label>
                                    <input type="text" name="item_unit_sales_price" value="{{ $itemUnitById->item_vat }}" class="form-control" required/>
                                    @if($errors->has('sales_price'))
                                        <span class="text-danger">{{ $errors->first('sales_price')}}</span>
                                    @endif
                                </div> -->

                                <br>                          
                                <div class="form-group">
                                    <input type="submit" class="btn btn-warning" value="Update"/>
                                </div>
                            </form>
                                               
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>
<script>
    document.forms['editForm'].elements['item_id'].value="{{ $itemUnitById->item_id }}"
    document.forms['editForm'].elements['tbl_item_unit_id_new'].value="{{ $itemUnitById->sales_price }}"
</script>
</div>
@endsection
