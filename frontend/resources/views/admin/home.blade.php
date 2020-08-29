@extends('layouts.template')

@section('template')
<!--
<section class="content-header">
    <h1>
        Dashboard
        <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
-->
<section class="content">

    <div class="row">
        @if($globalSettings->payment_notification=='on')
          <div class="col-md-12">
              <div class="alert alert-danger">
                <strong>প্রিয় গ্রাহক!</strong><br> আপনার বকেয়া বিল {{$globalSettings->service_charge}}/= বিকাশের মাধ্যমে পরিশোধ করুন
                <br> পরিশোধের শেষ তারিখঃ {{ date('M d, Y',strtotime($globalSettings->next_service_hault_date. ' -1 day')) }} <br>
                <b> বিকাশ নাম্বারঃ {{$globalSettings->bkash_no}} (পার্সোনাল) </b></br></br>
                ধন্যবাদ।
              </div>
          </div>
        @endif
        @if(date('Y-m-d') < $globalSettings->next_service_hault_date)
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/sales') }}" target="">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><i class="fa fa-cart-plus"></i></h3>
                <p> Sale</p>
              </div>
              <div class="icon">
                <i class="fa fa-cart-plus"></i>
              </div>
            </div>
          </a>  
        </div>
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/purchase') }}" target="">
            <div class="small-box bg-green">
              <div class="inner">
                <h3><i class="fa fa-cart-arrow-down"></i></h3>
                <p> Purchase</p>
              </div>
              <div class="icon">
                <i class="fa fa-cart-arrow-down"></i>
              </div>
            </div>
          </a>  
        </div>
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('money-receipt/create') }}" target="">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><i class="fa fa-copy"></i></h3>
                <p>Due Receive</p>
              </div>
              <div class="icon">
                <i class="fa fa-copy"></i>
              </div>
            </div>
          </a>  
        </div>
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/report/account-statement') }}" target="">
            <div class="small-box bg-red">
              <div class="inner">
                <h3><i class="fa fa-dollar"></i></h3>
                <p>Ledger</p>
              </div>
              <div class="icon">
                <i class="fa fa-dollar"></i>
              </div>
            </div>
          </a>  
        </div>
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/customer/create') }}" target="">
            <div class="small-box bg-orange">
              <div class="inner">
                <h3><i class="fa fa-user-plus"></i></h3>
                <p>Customer</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-plus"></i>
              </div>
            </div>
          </a>  
        </div>
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/supplier/create') }}" target="">
            <div class="small-box bg-purple">
              <div class="inner">
                <h3><i class="fa fa-handshake-o"></i></h3>
                <p>Supplier</p>
              </div>
              <div class="icon">
                <i class="fa fa-handshake-o"></i>
              </div>
            </div>
          </a>  
        </div>

        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/item') }}" target="">
            <div class="small-box bg-green">
              <div class="inner">
                <h3><i class="fa fa-laptop"></i></h3>
                <p>Item</p>
              </div>
              <div class="icon">
                <i class="fa fa-laptop"></i>
              </div>
            </div>
          </a>  
        </div>
        <div class="col-lg-3 col-xs-3">
          <a href="{{ url('/report/current-stock') }}" target="">
            <div class="small-box bg-blue">
              <div class="inner">
                <h3><i class="fa fa-cubes"></i></h3>
                <p>Stock</p>
              </div>
              <div class="icon">
                <i class="fa fa-cubes"></i>
              </div>
            </div>
          </a>  
        </div>

         <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-cart-plus"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Today's Sales </span>
              <span class="info-box-number">BDT {{ number_format($sale_today,2) }} </span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-life-ring"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Today's Sale Discount</span>
              <span class="info-box-text"></span>
              <span class="info-box-number">BDT {{ number_format($sale_discount,2) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-life-ring"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Today's Due Sale</span>
              <span class="info-box-number">BDT @foreach($customer_due_today as $customer_due) @if($customer_due->debit-$customer_due->credit > 0) {{ number_format($customer_due->debit-$customer_due->credit,2) }} @else 0.00 @endif  @endforeach</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue "><i class="fa fa-reply"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Today's Due Recovered</span>
              <span class="info-box-number">BDT {{ number_format($due_received_today,2) }}</span>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-bell-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Today's Expense </span>
              <span class="info-box-number">BDT {{ number_format($expense_today,2) }} </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-mobile"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Invetory Item</span>
              <span class="info-box-number">{{ number_format($inventory_total_qty,2) }} Pcs</span>
              <span class="info-box-number">BDT  {{number_format($stockStore,2)}}</span>
            </div>
          </div>
          <!-- /.info-box -->
        </div>  
        
        

        
      <a href="{{ url('/report/grand-receivable/') }}" target="_blank">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green "><i class="fa fa-tag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Due Receivable</span>
              <span class="info-box-number">BDT {{number_format($due_receivable,2)}} </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div> 
      </a>
        
<!--         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple "><i class="fa fa-share"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Due Paid</span>
              <span class="info-box-text">{{ date('Y-m-d') }}</span>
              <span class="info-box-number">BDT {{ number_format($due_paid_today,2) }}</span>
            </div>
          </div>
        </div>  --> 
<!--         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-gray "><i class="fa fa-handshake-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Vendor</span>
              <span class="info-box-number">{{ $supplier_count }}</span>
            </div>
    
          </div>
        </div>  -->


<!--         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Profit Today</span>
              <span class="info-box-text">{{ date('Y-m-d') }}</span>
              <span class="info-box-number">BDT {{ number_format($profit_today,2) }}</span>
            </div>
       
          </div>

        </div>  -->
    
    </div>

    <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">      
          <!-- TO DO List -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Last 3  Sales</h3>
                </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                <ul class="todo-list">
              
                    @foreach($last_three_sales as $last_three_sale)
                    <li>
                    <!-- drag handle -->
                    <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                    <!-- checkbox -->
                    
                    <!-- todo text -->
                    <span class="text">{{ $last_three_sale->item_name }}</span>
                    <!-- Emphasis label -->
                    <small class="label label-primary"> {{ $last_three_sale->sales_date }}</small>
                    <!-- General tools such as edit or delete-->

                    </li>
                    @endforeach

               </ul>
            </div>
            <!-- /.box-body -->

          </div>

        </section>
        <section class="col-lg-6 connectedSortable">      
          <!-- TO DO List -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Last 3 Purchases</h3>
                </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list">
                  @foreach($last_three_purchases as $last_three_purchase)
                    <li>
                    <!-- drag handle -->
                    <span class="handle">
                            <i class="fa fa-ellipsis-v"></i>
                        </span>
                    <!-- checkbox -->
                    
                    <!-- todo text -->
                    <span class="text">{{ $last_three_purchase->item_name }}</span>
                    <!-- Emphasis label -->
                    <small class="label label-primary"> {{ $last_three_purchase->purchase_date }}</small>
                    <!-- General tools such as edit or delete-->

                    </li>
                    @endforeach
               </ul>
            </div>
            <!-- /.box-body -->

          </div>

        </section>
        <!--#### START Bar Chart ####-->

        <!--#### END Bar Chart ####-->

        <!--#### START Doughnut Chart ####-->

        <!--#### END Doughnut Chart ####-->
      </div>
      <div class="row">
        <section class="col-lg-6 connectedSortable">      
            <div class="box box-warning">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Top 5 Sold Items</h3>
                </div>
            <div class="box-body">
              <ul class="todo-list">
                @php
                for($row=0;$row<count($top_5_item_sold);$row++){
                @endphp
                    <li><span class="handle"><i class="fa fa-ellipsis-v"></i></span>
                      <span class="text">{{ $top_5_item_sold[$row][0] }}</span>
                      <small class="label label-success"> {{ $top_5_item_sold[$row][1] }}</small>
                    </li>
                @php }  @endphp
               </ul>
            </div>

          </div>

        </section>

        <section class="col-lg-6 connectedSortable">      
            <div class="box box-warning">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Top 5 Recurring Customers</h3>
                </div>
            <div class="box-body">
              <ul class="todo-list">
                @php
                for($row=0;$row<count($top_5_customer);$row++){
                @endphp
                    <li><span class="handle"><i class="fa fa-ellipsis-v"></i></span>
                      <span class="text">{{ $top_5_customer[$row][0] }}</span>
                      <small class="label label-success"> {{ $top_5_customer[$row][1] }}</small>
                    </li>
                @php }  @endphp
               </ul>
            </div>

          </div>

        </section>

        @endif

    </div>
</section>

@endsection