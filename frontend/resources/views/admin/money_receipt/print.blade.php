@include('admin.sales.GetCurrencyClass')

@extends('admin.layouts.template')

@section('template')

<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>

<style type="text/css">
  th ,td{
    padding: 4px;
    font-size: 85%;
  }
</style>

<section class="content-header">
    <h1>
        MONEY RECEIPT
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Invoice</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">

<!-- Main content -->
<section class="invoice" >
<!-- title row -->

<div  style="margin: 3%;">

<div class="row">
  <div class="col-xs-12">
    <div class="col-xs-2">
      <!-- <img src="{{ asset($globalSettings->logo) }}" height="55" width="210" style="float:left"> -->
    </div>

    <div class="col-xs-8" style="text-align: center;">
      <!-- <img src="{{ asset('public/image/invoice.png') }}" height="85" width="85" style="float:right"> -->
      <div style="font-size: 85%">
        <h4 style="margin: 0px; padding: 0px;"><strong>{{ strToUpper($globalSettings->company_name) }}</strong></h4>
        {!!$globalSettings->address!!}<br>
        @if($globalSettings->website != '')
        Website: {{$globalSettings->website}}<br>
        @endif
        Mobile: {{$globalSettings->mobile}}, Tel: {{$globalSettings->phone}}
      </div>
    </div>

    <div class="col-xs-2">
      <!-- <img src="{{ asset($globalSettings->logo) }}" height="75" width="250" style="float:left"> -->
    </div>

  </div>

  <!-- /.col -->
</div>
<div class="row" style="margin-top: 2%">
  <div class="col-xs-12">
    <div class="col-xs-12" style="text-align:center">
      <h5><strong>MONEY RECEIPT</strong></h5>
    </div>
  </div>
</div>

<div class="row" style="margin-top: 2%">
  <div class="col-xs-12">
    <div class="col-xs-6" style="text-align:left; font-size: 85%">
      &nbsp <strong>Payment Method: {{ $money_receipt->bank_name }}</strong>
    </div>
    <div class="col-xs-6" style="text-align:right; font-size: 85%">
      <strong>
        M.R No.: {{ $globalSettings->invoice_prefix."-MR-".str_pad($money_receipt->mr_id, 8, '0', STR_PAD_LEFT) }}<br>
        Date: {{ date('F d, Y',strtotime($money_receipt->date)) }}
      </strong>
    </div>
  </div>
</div>


    <div class="row" style="margin-top: 2%">
      <div class="col-xs-12">
        <div class="col-xs-12">
          <div style="font-size: 85%">
            <table width="100%">
              <tr>
                <th style="width: 30%">Received with thanks from:</th>
                <th>{{ $customer->customer_name.' ('.$globalSettings->invoice_prefix."-".str_pad($customer->customer_code, 8, '0', STR_PAD_LEFT).')' }}</th>
              </tr>
              <tr>
                <th>Address:</th>
                <th>{{ $customer->address }}</th>
              </tr>
              <tr>
                <th>Narration:</th>
                <th>{{ $money_receipt->narration }}</th>
              </tr>
              <tr>
                <th>Description:</th>
                <th>{{ $money_receipt->payment_by }}</th>
              </tr>
              <tr>
                <th>On Account of the supply of:</th>
                <th>{{ $money_receipt->on_account_of_supply }}</th>
              </tr>
              <tr>
                <th>Amount:</th>
                <th>TK. {{ number_format($money_receipt->amount,2) }}</th>
              </tr>
            </table>
          </div>

          <div style="margin-top: 10px;font-size: 85%">
            &nbsp<b>In Words (BDT TK.): {{ getCurrency($money_receipt->amount) }} Only</b>
          </div>

        </div>

      </div>
      

    </div>
    
    <div class="row" style="margin-top: 3%">
      <div class="col-xs-12">
        <div class="col-xs-4">
          <div style="text-align:center; font-size: 85%">
            ______________________<br>
              Customer's Signature
          </div>
        </div>
        <div class="col-xs-4" style="text-align:center">
        </div>
        <div class="col-xs-4" style="text-align:right">
          <div style="text-align:center; font-size: 85%">
            ______________________<br>
              Receiver: {{ $money_receipt->name }}
          </div>
      </div>

      </div>

    </div>

        <div class="row" style="margin-top: 2%">
      <div class="col-xs-12">
        <div class="col-xs-5">
          <div style="text-align:left; font-size: 55%">
            @php date_default_timezone_set("Asia/Dhaka"); @endphp
            Printed at: {{date("F d, Y h:i:sa")}} 
          </div>
        </div>
        <div class="col-xs-2" style="text-align:center">


        </div>
        <div class="col-xs-5" style="text-align:right">
          <div style="text-align:right; font-size: 55%">
            <!-- Software By: V-Link Network -->
          </div>
        </div>

      </div>

    </div>


</div>
</section>
</section>

<!-- End Main Content -->

<script>
    window.print();
</script>
@endsection

