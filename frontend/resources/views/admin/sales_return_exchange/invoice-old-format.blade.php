@include('admin.sales.GetCurrencyClass')

@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->

<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>

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

<!-- Main content -->
<section class="invoice">
<!-- title row -->


<div class="row">
  <div class="col-xs-12">
    <div class="col-xs-6">
      <img src="{{ asset('public/image/logo.png') }}" height="105" width="250" style="float:left">
    </div>
    <!--
    <div class="col-xs-6">
      <p style="float:right;font-size:130%"><u>CASH</u> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <br> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<u>MEMO</u></p>
    </div>
    -->
    <div class="col-xs-6">
      <img src="{{ asset('public/image/invoice.png') }}" height="85" width="85" style="float:right">
    </div>

  </div>

  <!-- /.col -->
</div>
<div class="row" style="margin-top: 3%">
  <div class="col-xs-12">
    <div class="col-xs-6">
      <address>
        Invoice No. {{ $salesMasters->voucher_ref }}<br>
        Customer Name: {{ $salesMasters->customer_name }}<br>
        Address: @if(!empty($salesMasters->address)){{ $salesMasters->address }}  @else   _ _ _ _ _ _ _ _ _ _ _ @endif
      </address>
    </div>
    <div class="col-xs-6" style="text-align:right">
      <address>
        Date: <!--{{ $salesMasters->sales_date }}-->{{ date("jS \of F Y ") }}<br>
        Sold By: @if(isset($sold_by)){{ $sold_by->name }}@endif<br>
        Cust. Cell No: @if(!empty($salesMasters->mobile_no)){{ $salesMasters->mobile_no }}  @else  _ _ _ _ _ _ _ _ _ _ _ @endif<br>
      </address>

  </div>

  </div>
  <!-- /.col -->
</div>
<!-- Table row -->

<section class="invoice-bg">
<div class="row">
  <div class="col-md-12">
      <img src="{{ asset('public/image/invoice-bg.png') }}">
  </div>
</div>     
</section>

<div class="row">
  <div class="col-xs-12 table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
      <tr>
        <th style="width:2%">Srl.</th>
        <th style="width:26%">Item</th>
        <th style="width:16%">Brand</th>
        <th style="text-align:right;width:2%">Qty</th>
        <th style="text-align:right;width:12%">Price</th>
        <th style="text-align:right;width:12%">Vat</th>
        <th style="text-align:right;width:12%">Amount</th>
      </tr>
      </thead>
      <tbody>
        @php $sub_total = 0 @endphp
        @foreach($salesDetail as $key => $salesDetails)
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $salesDetails->item_name }}</td>
            <td>{{ $salesDetails->brand_name }}</td>
            <td style="text-align:right">{{ $salesDetails->quantity }}</td>
            <td style="text-align:right">{{ number_format($salesDetails->sales_price,2) }}</td>
            <td style="text-align:right">{{ number_format(($salesDetails->sales_price * $salesDetails->quantity) * ($salesDetails->item_vat/100),2) }}</td>
            <td style="text-align:right">
              {{ number_format(($salesDetails->sales_price * $salesDetails->quantity)+($salesDetails->sales_price * $salesDetails->quantity) * ($salesDetails->item_vat/100),2) }}
            </td>
        </tr>
        @php $sub_total += $salesDetails->sales_price * $salesDetails->quantity @endphp
        @endforeach
        
      </tbody>
    </table>
  </div>
  <!-- /.col -->
</div>

    <div class="row" style="margin-top: 5%">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        
        <p class="" style="margin-top: 10px;">
          <b>In Words: </b>{{ getCurrency($sub_total-$salesMasters->discount) }} Taka
        </p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
       

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Discount:</th>
              <td>{{ number_format($salesMasters->discount,2) }}</td>
            </tr>
            <tr>
              <th>Total</th>
              <td>{{ number_format($sub_total-$salesMasters->discount,2) }}</td>
            </tr>
            <tr>
              <th>Amount Paid:</th>
              <td>{{ number_format($salesMasters->advanced_amount,2) }}</td>
            </tr>
            <tr>
              <th>Balance Due:</th>
              <td>{{ number_format($salesMasters->memo_total - $salesMasters->advanced_amount - $salesMasters->discount,2) }}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>

<!-- /.row -->
<div style="margin-top: 1%">
    <p style="font-size: 90%; text-align: left">
            * Sold goods are not returnable.<br>
            Thank you for your business.
    </p>

</div>
<div style="margin-top: 40%">
    <p style="font-size: 90%; text-align: center">
            {{$globalSettings->address}} <br>
            Mobile: {{$globalSettings->mobile}} , Email: {{$globalSettings->email}} <br>     
            © Copright 2018 | All Rights Reserved To {{$globalSettings->company_name}} 
    </p>
</div>
  <!-- accepted payments column -->
  <div class="col-xs-7">

  </div>
  <!-- /.col -->
  <div class="col-xs-5">

</div>

</section>
</section>

<!-- End Main Content -->

<script>
    window.print();
</script>
@endsection

