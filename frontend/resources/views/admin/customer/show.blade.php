@extends('admin.layouts.template')

@section('template')
<style type="text/css">
    th,td{
        padding: 5px;
    }
</style>
<section class="content-header">
    <h1>
        CUSTOMER
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Customer</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<script type="text/javascript">
    function myFunction(){
        //window.print();
    }
</script>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
    @media print{
       .noprint{
           display:none;
       }
    }
</style>
<section class="content" style="margin: 5%">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-print-none">
                        <button id="print" class="btn btn-default btn-sm noprint" onclick="myFunction()">Print</button>
                    </div>

                    <h2><img style="float:right" src="{{ asset($customerById->customer_image) }}" height="140" width="140">
                    {{ $customerById->customer_name }}</h2>
                    
                    <br>
                    <table border="1" width="60%">
                        <tr>
                            <th>ID</th>
                            <td>{{ $globalSettings->invoice_prefix."-".str_pad($customerById->customer_code, 8, '0', STR_PAD_LEFT) }}</td>
                        </tr>

                      

                        <tr>
                            <th>Mobile</th>
                            <td>{{ $customerById->mobile_no }}</td>
                        </tr>

                    
                        <tr>
                            <th>Category</th>
                            <td>{{ $customerById->cat_name }}</td>
                        </tr>
                    </table>


                </div>
            </div>    
            <div class="row">    
                <div class="col-md-3">
                    
                </div>
                <div class="col-md-3">
                    
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('update'))
                        <div class="alert alert-warning" id="update">
                            {{Session::get('update')}}
                            @php
                            Session::forget('update');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('delete'))
                        <div class="alert alert-danger" id="delete">
                            {{Session::get('delete')}}
                            @php
                            Session::forget('delete');
                            @endphp
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection