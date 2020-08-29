<!--
Project     : SHOILPIK LIMITED - ERP
Author URI  : http://v-linknetwork.com
Description : VIPOS SOFTWARE CREATED BY V-LINK NETWORK. 
Author      : V-LINK NETWORK | A LEADING SOFTWARE COMPANY IN CHITTAGONG, BANGLADESH. 
Developer   : Amanat Juwel
Version     : 1.0
-->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$globalSettings->company_name}} @yield('title')</title>
    <link rel="shortcut icon" type="image/png" href='{{ url("$globalSettings->favicon") }}' />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <!-- {{ asset('public/assets/css/bootstrap.min.css') }} -->
    <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/components-rounded.min.css') }}">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/admin/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/css/select2.min.css') }}">
    <script src="{{ asset('public/admin/js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    @yield('style')
</head>

<body class="hold-transition skin-{{$globalSettings->theme}} sidebar-mini @if($globalSettings->full_sidebar == 1)sidebar-collapse @endif">
    <div class="wrapper">
        <!-- Header -->
        <header class="main-header">
            <!-- Logo -->            
            <a href="{{ url('/admin/') }}" class="logo hidden-xs">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src='{{ asset("$globalSettings->favicon") }}' height="25" width="25"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src='{{ asset("$globalSettings->system_logo") }}' height="50" width="120"></span>
            </a> 
            <nav class="navbar">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!--START MIN REORDER STOCK NOTIFICATION -->
                        @php $min_count = 0; @endphp
                        @if($notification_items != null)
                        @foreach($notification_items as $item)
                            @if($item->current_stock < $item->reorder_level_min)
                                @php $min_count += 1; @endphp
                            @endif
                        @endforeach
                        @if($min_count>0)
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="fa fa-bell-o"></i>
                              <span class="label label-warning">{{$min_count}}</span>
                            </a>
                            <ul class="dropdown-menu">
                              <li class="header">You have {{$min_count}} items in low stock</li>
                              <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                  @foreach($notification_items as $item)
                                      @if($item->current_stock < $item->reorder_level_min)
                                          <li>
                                            <a href="#">
                                              <i class="fa fa-warning text-yellow"></i> {{ $item->item_name }}
                                            </a>
                                          </li>
                                      @endif
                                  @endforeach
                                </ul>
                              </li>
                            </ul>
                          </li>
                          @endif
                         @endif
                        <!--END MIN REORDER NOTIFICATION -->
                        
                        <!--START UNMOVED ITEM STOCK NOTIFICATION -->
                        @if($last_stock_out!=null)
                        @php $item_count = 0; @endphp
                        @foreach($last_stock_out as $data)
                            @if($data->stock_change_date < \Carbon\Carbon::now()->subMonths(1))
                                @php $item_count += 1; @endphp
                            @endif
                        @endforeach
                        @if($item_count>0)
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="fa fa-envelope-o"></i>
                              <span class="label label-danger">{{$item_count}}</span>
                            </a>
                            <ul class="dropdown-menu">
                              <li class="header">You have {{$item_count}} items unmoved in last 3 months</li>
                              <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                 @foreach($last_stock_out as $data)
                                    @if($data->stock_change_date < \Carbon\Carbon::now()->subMonths(1))
                                          <li>
                                            <a href="#">
                                              <i class="fa fa-shopping-cart text-red"></i> {{ $data->item_name }} ({{$data->current_stock.' '.$data->unit}})<br>Last transit date: {{ date('M d,Y',strtotime($data->stock_change_date)) }}
                                            </a>
                                          </li>
                                      @endif
                                  @endforeach
                                </ul>
                              </li>
                            </ul>
                        </li>
                        @endif
                        @endif
                        <!--END UNMOVED ITEM STOCK NOTIFICATION --> 

                        @if (Auth::guest())
                            <li class="dropdown user user-menu">
                                <a href="{{ url('/admin/login') }}">Login</a>
                            </li>
                        @else
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span class="hidden-xs">Hello, {{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ asset('public/admin/images/boss.png')}}" class="img-circle" alt="User Image">
                                    <p>
                                        Hello , <a style="color: #fff;" href="{{ url('/admin/profile') }}">{{ Auth::user()->name }}</a>
                                        @if(Auth::user()->role == 'admin')
                                        <small>Administrator</small>
                                        @else
                                        <small>Employee</small>
                                        @endif
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ url('/admin/profile') }}" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Sign out</a>
                                    </div>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER -->
        <!-- SIDEBAR -->
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="{{ (Request::path() == 'admin') ? 'active' : '' }}">
                        <a href="{{ url('/admin') }}">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="{{ (Request::path() == 'admin/orders') ? 'active' : '' }}"><a href="{{ url('/admin/orders') }}"><i class="fa fa-plus text-blue"></i> <span>Orders</span></a></li>

                    <li class="treeview {{ (Request::path() == 'admin/sales') ? 'active' : '' }} {{ Request::is('admin/sales/') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-cart-arrow-down text-green"></i>
                            <span>Sales</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-blue"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/sales') }}"><i class="fa fa-plus text-green"></i> New Sale</a></li>
                            <li><a href="{{ url('/admin/sales/sales-details') }}"><i class="fa fa-list-ul text-green"></i> Sales List</a></li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('admin/sales/return/exchange/*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-mail-reply-all text-green"></i>
                            <span>Sale Return & Exchange</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-blue"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                             <li><a href="{{ url('admin/sales-return/exchange/create') }}"><i class="fa fa-exchange text-blue"></i> Create</a></li>
                            <li><a href="{{ url('admin/sales-return/exchange') }}"><i class="fa fa-list-ul text-red"></i> List</a></li>
                        </ul>
                    </li>
                    <li class="treeview {{ (Request::path() == 'admin/purchase') ? 'active' : '' }} {{ Request::is('admin/purchase/*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-cart-plus text-blue" style=""></i>
                            <span>Purchase</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/purchase') }}"><i class="fa fa-plus"></i> Add New</a></li>
                            <li><a href="{{ url('/admin/purchase/purchase-details') }}"><i class="fa fa-list-ul"></i> Purchase List</a></li>
                        </ul>
                    </li>
                    <li class="treeview {{ (Request::path() == 'admin/purchase-return') ? 'active' : '' }} {{ Request::is('admin/purchase-return/*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-mail-reply-all text-blue"></i>
                        <span>Purchase Return</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right text-blue"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/purchase-return') }}"><i class="fa fa-share text-red"></i> Return Entry</a></li>
                        <li><a href="{{ url('/admin/purchase-return/list') }}"><i class="fa fa-list-ul text-red"></i> Return List</a></li>
                    </ul>
                    </li>
                    <li class="treeview {{ (Request::path() == 'admin/money-receipt') ? 'active' : '' }} {{ Request::is('admin/money-receipt/*') ? 'active' : '' }} {{ Request::is('admin/supplier/payment/index') ? 'active' : '' }} {{ Request::is('admin/bank/transfer') ? 'active' : '' }} {{ Request::is('admin/bank/transaction') ? 'active' : '' }} ">
                        <a href="#">
                            <i class="fa fa-dollar text-red"></i>
                            <span>Transactions</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-green"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/money-receipt/create') }}"><i class="fa fa-plus text-green"></i> Due Receive</a></li>
                            <li><a href="{{ url('/admin/supplier/payment/index') }}"><i class="fa fa-plus text-orange"></i> Supplier Payment</a></li>
                            <li><a href="{{ url('/admin/bank/transfer/') }}"><i class="fa fa-exchange text-blue"></i> Cash-Bank-Other Tr.</a></li>
                        </ul>
                    </li>
                    
                    
                    <li class="treeview {{ (Request::path() == 'admin/item') ? 'active' : '' }} {{ Request::is('admin/item/*') ? 'active' : '' }} {{ (Request::path() == 'admin/batch') ? 'active' : '' }} {{ Request::is('admin/batch/*') ? 'active' : '' }} {{ (Request::path() == 'admin/category') ? 'active' : '' }} {{ (Request::path() == 'admin/sub-category') ? 'active' : '' }} {{ (Request::path() == 'admin/sub-sub-category') ? 'active' : '' }} {{ (Request::path() == 'admin/brand') ? 'active' : '' }} {{ (Request::path() == 'admin/unit') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-gears text-purple"></i>
                            <span>Item Setup</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-purple"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            
                            <li class="treeview {{ (Request::path() == 'admin/item') ? 'active' : '' }} {{ Request::is('admin/item/*') ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cubes"></i>
                                    <span>Item Info</span>
                                    <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('/admin/item/create') }}"><i class="fa fa-plus"></i> Add Item</a></li>
                                    <li><a href="{{ url('/admin/item') }}"><i class="fa fa-list-ul"></i> All Items</a></li>
                                </ul>
                            </li> 
                            <li class="treeview {{ Request::is('admin/batch') ? 'active' : '' }} {{ Request::is('admin/admin/batch/*') ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cart-plus text-green" style=""></i>
                                    <span>Batch</span>
                                    <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right text-green"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('/admin/batch/create') }}"><i class="fa fa-plus"></i>Add New </a></li>
                                    <li><a href="{{ url('/admin/batch/') }}"><i class="fa fa-plus"></i>All batch</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ url('/admin/category') }}"><i class="fa fa-list-ul"></i> Manage Category</a></li>
                            <li><a href="{{ url('/admin/sub-category/') }}"><i class="fa fa-tasks"></i> Manage Sub-Category</a></li>
                            <li><a href="{{ url('/admin/sub-sub-category/') }}"><i class="fa fa-tasks"></i> Manage Sub-Sub-Category</a></li>
                            <li><a href="{{ url('/admin/brand') }}"><i class="fa fa-shirtsinbulk"></i> Manage Brand</a></li>
                            <li><a href="{{ url('/admin/unit') }}"><i class="fa fa-tint"></i> Manage Unit</a></li>

                        </ul>
                    </li>
                    <!--<li class="treeview">-->
                    <!--    <a href="#">-->
                    <!--        <i class="fa fa-money text-green"></i>-->
                    <!--        <span>Revenue </span>-->
                    <!--        <span class="pull-right-container">-->
                    <!--      <i class="fa fa-angle-left pull-right text-red"></i>-->
                    <!--    </span>-->
                    <!--    </a>-->
                    <!--    <ul class="treeview-menu">-->
                    <!--        <li><a href="{{ url('/admin/income/') }}"><i class="fa fa-list"></i> Revenue List</a></li>-->
                    <!--        <li><a href="{{ url('/admin/income/head/') }}"><i class="fa fa-support"></i> Revenue Account</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <li class="treeview {{ Request::is('admin/bank/*') ? 'active' : '' }} {{ Request::is('admin/customer') ? 'active' : '' }} {{ Request::is('admin/customer/*') ? 'active' : '' }} {{ Request::is('admin/cust/*') ? 'active' : '' }} {{ Request::is('admin/supplier') ? 'active' : '' }} {{ Request::is('admin/supplier/*') ? 'active' : '' }} {{ Request::is('admin/expense/*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-bank text-blue"></i>
                            <span>Head of Accounts</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-blue"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/bank/account/') }}"><i class="fa fa-support"></i> Cash/Bank/Other Ac.</a></li>
                            <li class="treeview {{ Request::is('customer') ? 'active' : '' }} {{ Request::is('customer/*') ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-users text-aqua"></i>
                                    <span>Customer</span>
                                    <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right text-aqua"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('/admin/customer/create') }}"><i class="fa fa-user-plus"></i> Add Customer</a></li>
                                    <li><a href="{{ url('/admin/customer') }}"><i class="fa fa-list-ul"></i> List Customers</a></li>
                                    <li><a href="{{ url('/admin/cust/category') }}"><i class="fa fa-list-ul"></i> Customer Category</a></li>
                                </ul>
                            </li> 
                            <li class="treeview {{ Request::is('admin/supplier') ? 'active' : '' }} {{ Request::is('admin/supplier/*') ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-handshake-o text-orange"></i>
                                    <span>Supplier</span>
                                    <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right text-orange"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('/admin/supplier/create') }}"><i class="fa fa-user-plus"></i> Add Supplier</a></li>
                                    <li><a href="{{ url('/admin/supplier') }}"><i class="fa fa-list-ul"></i> List Suppliers</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview {{ Request::is('admin/report/*') ? 'active' : '' }} {{ Request::is('admin/reports/*') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-line-chart text-maroon"></i>
                            <span>Report</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-maroon"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('admin/report/statement/cash-book') }}"><i class="fa fa-dollar"></i>Cash Book</a></li>
                            <li><a href="{{ url('/admin/report/grand-receivable/') }}" target="_blank"><i class="fa fa-list-ul"></i> Due Receivable</a></li>
                            <li><a href="{{ url('/admin/report/balance-sheet/') }}" target=""><i class="fa fa-file"></i> Balance Sheet</a></li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-money"></i>
                                    <span>Sales Report</span>
                                    <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <!-- <li><a href="{{ url('/admin/report/todays-sales') }}"><i class="fa fa-list-ul"></i>Item Wise- Today's Sale</a></li> -->
                                    <li><a href="{{ url('/admin/report/memo-wise-date-to-date-sales') }}"><i class="fa fa-list-ul"></i> Invoice Wise - Sales</a></li>
                                    <li><a href="{{ url('/admin/report/date-to-date-sales') }}"><i class="fa fa-list-ul"></i> Item Wise - Sales</a></li>
                                    <li><a href="{{ url('/admin/report/date-to-date-sales/to-customer') }}"><i class="fa fa-list-ul"></i> To Customer </a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-money"></i>
                                    <span>Purchase Report</span>
                                    <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('/admin/report/date-to-date-purchase/from-supplier') }}"><i class="fa fa-list-ul"></i> From Supplier </a></li>
                                    <li><a href="{{ url('/admin/report/date-to-date-purchase') }}"><i class="fa fa-list-ul"></i> Item Wise </a></li>
                                </ul>
                            </li>
                            
                            <li><a href="{{ url('/admin/report/date-to-date-expenses') }}"><i class="fa fa-list-ul"></i> Expense Report</a></li>
                            <li><a href="{{ url('/admin/report/account-statement') }}"><i class="fa fa-list-ul"></i> Ledger Book</a></li>
                            
                            <li class="{{ Request::is('reports/income-statement') ? 'active' : '' }}"><a href="{{ url('admin/reports/income-statement') }}"><i class="fa fa-plus text-green"></i> Profit & Loss</a></li>
                            <li class="{{ Request::is('reports/daily-cash-sheet') ? 'active' : '' }}"><a href="{{ url('admin/reports/daily-cash-sheet') }}"><i class="fa fa-plus text-green"></i> Daily Cash Sheet</a></li>
                            
                            <li class="treeview  {{ Request::is('admin/report/finished-product/*') ? 'active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cart-plus"></i>
                                    <span>Stock & Inventory</span>
                                    <span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('/admin/report/finished-product/stock-register') }}"><i class="fa fa-list-ul"></i> Stock Register</a></li>
                                    <li><a href="{{ url('/admin/report/finished-product/current-stock') }}"><i class="fa fa-list-ul"></i> Current Stock</a></li>
                                    <!-- <li><a href="{{ url('admin/report/finished-product/inventory') }}"><i class="fa fa-list-ul "></i> Inventory</a></li> -->
                                </ul>
                            </li>
                            <!-- <li><a href="{{ url('/admin/report/inventory/descriptive') }}"><i class="fa fa-list-ul "></i> By Category</a></li>  -->
                        
                                                 
                        </ul>
                    </li>
                  <!--    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-comments-o text-green"></i>
                            <span>SMS</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-green"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">   
                            <li>
                                <a href="{{ url('/admin/sms/send-sms') }}">
                                    <i class="fa fa-paper-plane-o "></i> <span>Send SMS</span>
                                </a>
                            </li>  
                            <li>
                                <a href="{{ url('/admin/sms/sent') }}">
                                    <i class="fa fa-history"></i> <span>Sent</span>
                                </a>
                            </li>  
                        </ul>
                    </li> --> 

                    <li class="treeview {{ (Request::path() == 'admin/pages') ? 'active' : '' }} {{ Request::is('admin/sliders') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-globe text-maroon"></i>
                            <span>Web</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-maroon"></i>
                        </span>
                        </a>
                       
                        <ul class="treeview-menu">
                            <li><a href="{{ url('/admin/pages') }}"><i class="fa fa-database"></i> <span>Page </span></a></li>   
                            <li><a href="{{ url('/admin/sliders') }}"><i class="fa fa-database"></i> <span>Slider </span></a></li>  
                        </ul>
                        
                    </li> 

                    <li class="treeview {{ (Request::path() == 'admin/settings') ? 'active' : '' }} {{ Request::is('admin/logs') ? 'active' : '' }} {{ (Request::path() == 'admin/users/*') ? 'active' : '' }} {{ (Request::path() == 'admin/database-backup') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-list-alt text-maroon"></i>
                            <span>Utilities</span>
                            <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right text-maroon"></i>
                        </span>
                        </a>
                       
                        <ul class="treeview-menu">
                            <!-- @if(Auth::guard('admin')->check())   -->
                            <li><a href="{{ url('/admin/database-backup') }}"><i class="fa fa-database"></i> <span>Database Backup</span></a></li>   
                            <li><a href="{{ url('/admin/users/manage-users') }}"><i class="fa fa-users"></i> <span>System Users</span></a></li> 
                            <li><a href="{{ url('/admin/logs') }}"><i class="fa fa-history"></i> <span>Logs</span></a></li>  
                            <li><a href="{{ url('/admin/settings') }}"><i class="fa fa-gears"></i> <span>Settings</span></a></li>  
                            <!-- @endif  -->
                        </ul>
                        
                    </li>           

              </ul>
            </section>
        </aside>
        <!-- END SIDEBAR -->
        <!-- CONTENT SECTION -->
        <div class="content-wrapper">
            <!-- Start Main Content -->
                @yield('template')
            <!-- End Main Content -->
        </div>
        <!-- / END CONTENT -->
        <!-- START FOOTER -->
        <footer class="main-footer hidden-xs">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        © Copright {{date('Y')}} | All Rights Reserved To {{ $globalSettings->company_name}}
                    </div>
                    <!-- <div class="col-md-4">
                        Software Support : 01675-711884</a>
                    </div> -->
                </div>
            </div>
        </footer>
        <!-- END FOOTER -->
        
        
        <script src="{{ asset('public/admin/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/admin/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('public/admin/js/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
        <script src="{{ asset('public/admin/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/admin/js/style.js') }}"></script>
        <script src="{{ asset('public/admin/js/custom.js') }}"></script>
        <script src="{{ asset('public/admin/js/select2.full.min.js') }}"></script>
        <!-- CK Editor -->
        <!--<script src="{{ asset('public/admin/js/ckeditor/ckeditor.js') }}"></script>-->
        <!-- CK Editor -->
        <script>
         
        function Initialize() {
            $('.select2').select2()
            //Tab key working in select2
            $('select').on("select2:close", function () { $(this).focus(); });
            } 
        </script>

        <script>
          $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
            //Tab key working in select2
            $('select').on("select2:close", function () { $(this).focus(); });
          })

          //Date picker
            $('#datepicker').datepicker({
              
              format: 'yyyy-mm-dd',
              autoclose: 'true',
            })
            $('#datepicker2').datepicker({
              
              format: 'yyyy-mm-dd',
              autoclose: 'true',
            })
            $(".date-picker").change(function() { 
                setTimeout(function() {
                    $(".date-picker").datepicker('hide'); 
                    $(".date-picker").blur();
                }, 50);
            });  
        </script>

        @yield('script')
        <!-- CK Editor -->
<!--          <script>
          $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('ckEditor')
            //bootstrap WYSIHTML5 - text editor
            $('.textarea').wysihtml5()
          })
        </script>  -->
        <!-- CK Editor -->

</body>

</html>