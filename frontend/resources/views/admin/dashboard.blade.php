@extends('admin.layouts.template')

@section('template')

<section class="content">
    
  <div class="row">
      <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Sale & Purchase </h3>
            </div>
            <div class="box-body">
              <a class="btn btn-app" href="{{url('admin/orders')}}">
                <span class="badge bg-teal">{{($new_order>0)?$new_order:null}}</span>
                <i class="fa fa-inbox text-purple"></i> Orders
              </a>
              <a class="btn btn-app" href="{{url('admin/sales')}}">
                <i class="fa fa-cart-arrow-down text-green"></i> Sales
              </a>
              <a class="btn btn-app" href="{{url('admin/sales-details')}}">
                <i class="fa fa-list-ul text-green"></i> Sales List
              </a>
              <a class="btn btn-app" href="{{url('admin/sales-return/exchange/create')}}">
                <i class="fa fa-cart-arrow-down text-red"></i> Sales Return
              </a>
              <a class="btn btn-app" href="{{url('admin/purchase')}}">
                <i class="fa fa-edit text-blue"></i> Purchase
              </a>
              <a class="btn btn-app" href="{{url('admin/purchase-details')}}">
                <i class="fa fa-list-ul text-blue"></i> Purchase List
              </a>
              <a class="btn btn-app" href="{{url('admin/purchase-return')}}">
                <i class="fa fa-cart-arrow-down text-red"></i> Purchase Return
              </a>
            </div>
          </div>
    </div>
    
    <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Item Setup</h3>
            </div>
            <div class="box-body">
              <a class="btn btn-app" href="{{url('admin/item/create')}}">
                <i class="fa fa-cubes text-purple"></i> Add Item
              </a>
              <a class="btn btn-app" href="{{url('admin/item')}}">
                <span class="badge bg-green">{{$item_count}}</span>
                <i class="fa fa-list text-purple"></i> Item List
              </a>
              <a class="btn btn-app" href="{{url('admin/category')}}">
                <i class="fa fa-copyright text-purple"></i> Category
              </a>
              <a class="btn btn-app" href="{{url('admin/sub-category')}}">
                <i class="fa fa-database text-purple"></i> Sub-Category
              </a>
              <a class="btn btn-app" href="{{url('admin/sub-sub-category')}}">
                <i class="fa fa-tasks text-purple"></i> Sub-Sub-Category
              </a>
              <a class="btn btn-app" href="{{url('admin/brand')}}">
                <i class="fa fa-shirtsinbulk text-purple"></i> Brand
              </a>
              <a class="btn btn-app" href="{{url('admin/unit')}}">
                <i class="fa fa-tint text-purple"></i> Unit
              </a>
              
            </div>
          </div>
    </div>
    
  </div>
  <div class="row">
    <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Transaction </h3>
            </div>
            <div class="box-body">
              <a class="btn btn-app" href="{{url('admin/money-receipt/create')}}">
                <i class="fa fa-money text-green"></i> Due Receive
              </a>
              <a class="btn btn-app" href="{{url('admin/supplier/payment/index')}}">
                <i class="fa fa-money text-red"></i> Supplier Payment
              </a>
              <a class="btn btn-app" href="{{url('admin/bank/transfer')}}">
                <i class="fa fa-exchange text-blue"></i> Transfer
              </a>
              
            </div>
          </div>
    </div>
    <div class="col-md-6">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Head of Account </h3>
            </div>
            <div class="box-body">
              <a class="btn btn-app" href="{{url('admin/bank/account')}}">
                <i class="fa fa-bank text-purple"></i> Cash/Bank
              </a>
              <a class="btn btn-app" href="{{url('admin/customer/create')}}">
                <i class="fa fa-users text-green"></i> Add Customer
              </a>
              <a class="btn btn-app" href="{{url('admin/customer')}}">
                <i class="fa fa-list text-green"></i> Customer List
              </a>

              <a class="btn btn-app" href="{{url('admin/supplier/create')}}">
                <i class="fa fa-user-plus text-yellow"></i> Add Supplier
              </a>
              <a class="btn btn-app" href="{{url('admin/supplier')}}">
                <i class="fa fa-list text-yellow"></i> Supplier List
              </a>
              
            </div>
          </div>
    </div>
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-yellow"><i class="fa fa-database"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Stock</span>
                <span class="info-box-number">{{number_format($item_count,0)}} Unit</span>
                <span class="info-box-number">৳ {{number_format($stock_and_store_value,0)}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
      </div>
    </section>
</section>

@endsection