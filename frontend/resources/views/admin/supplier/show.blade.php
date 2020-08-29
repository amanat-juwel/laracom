@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Supplier 
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Supplier </li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<script>
    //window.print();
</script>
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">

                    <h2>
                    {{ $supplierById->sup_name }}</h2>
                    
                    <br>
                    <p><strong>Address:</strong> {{ $supplierById->sup_address }}</p>  
                    <p><strong>Mobile:</strong> {{ $supplierById->sup_phone_no }}</p>  
                    <p><strong>Email:</strong> {{ $supplierById->sup_email }}</p>  
                    <p><strong>Note: </strong>{{ $supplierById->note }}</p> 
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