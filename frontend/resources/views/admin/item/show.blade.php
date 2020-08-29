@extends('admin.layouts.template')

@section('template')
<style type="text/css">
    th,td{
        padding: 5px;
    }
</style>
<section class="content-header">
    <h1>ITEM</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Item</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<script type="text/javascript">
    function myFunction(){
        window.print();
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
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-print-none">
                        <button id="print" class="btn btn-info btn-sm noprint" onclick="myFunction()">Print</button>
                        <hr>
                    </div>
                </div>
            </div>    
            <div class="row">    
                <div class="col-md-12">
                    <strong>Item:</strong> {{$item->item_name}}<hr>
                    <strong>Code:</strong> {{ str_pad($item->item_code, 4, '0', STR_PAD_LEFT) }}<hr>
                    <strong>Catagory:</strong> {{ $item->cata_name }} @if(!empty($item->sub_cata_name)){{ ' => '.$item->sub_cata_name  }} @endif @if(!empty($item->sub_sub_cata_name)){{ ' => '.$item->sub_sub_cata_name  }} @endif<hr>
                    <strong>Brand:</strong> {{ $item->brand_name }}<hr>
                    <strong>Unit:</strong> {{ $item->unit }}<hr>
                    <strong>Description:</strong><br>
                    {!!$item->description!!}<hr>
                    <strong>Specification:</strong><br>
                    {!!$item->specification!!}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection