@include('sales.GetCurrencyClass')

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
<style type="text/css">
  th ,td{
    padding: 4px;
    font-size: 75%;
  }
</style>
<section class="content-header">
    <h1>
        INVOICE
        
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

<div  style="margin: 5%;">

<div class="row">
  <div class="col-xs-12">
    <div class="col-xs-2">
      <!-- <img src="{{ asset($globalSettings->logo) }}" height="55" width="210" style="float:left"> -->
    </div>

    <div class="col-xs-8" style="text-align: center;">
      <!-- <img src="{{ asset('public/image/invoice.png') }}" height="85" width="85" style="float:right"> -->
      <div style="font-size: 75%">
        <h4 style="margin: 0px; padding: 0px;"><strong>{{ strToUpper($globalSettings->company_name) }}</strong></h4>
        {!!$globalSettings->address!!}<br>
        @if($globalSettings->website != '')
        Website: {{$globalSettings->website}}<br>
        @endif
        Mobile: {{$globalSettings->mobile}}<br>
      </div>
    </div>

    <div class="col-xs-2">

    </div>

  </div>

</div>
<div class="row" style="margin-top: 3%">
  <div class="col-xs-12">
    <div class="col-xs-5">
      <div style="font-size: 75%">
        ID     : {{ $globalSettings->invoice_prefix."-".str_pad($salesMasters->customer_code, 8, '0', STR_PAD_LEFT) }}<br>
        Name   : {{ $salesMasters->customer_name }}<br>
        Phone  : @if(!empty($salesMasters->phone)){{ $salesMasters->phone }}  @endif<br>
        Cell No: @if(!empty($salesMasters->mobile_no)){{ $salesMasters->mobile_no }}  @endif<br>
        Address: @if(!empty($salesMasters->address)){{ $salesMasters->address }}     @endif<br>
      </div>
    </div>
    <div class="col-xs-2" style="text-align:center">
      <h5><strong>SALES BILL</strong></h5>
    </div>
    <div class="col-xs-5" style="text-align:right">
      <div style="font-size: 75%">
        Invoice No: {{ $globalSettings->invoice_prefix."-BI-".str_pad($salesMasters->sales_master_id, 8, '0', STR_PAD_LEFT) }}<br>
        Invoice Date: {{ date('M-d-Y', strtotime($salesMasters->sales_date)) }}<br>
       <!--  Delivery Date: {{$salesMasters->delivery_date}}<br> -->
      </div>
  </div>

  </div>

</div>


<div class="row" style="margin-top: 2%">
  <div class="col-xs-12 table-responsive">
    <div class="col-xs-12">
      <table border="1" style="width:100%">
        <thead>
        <tr>
          <th style="width:5%">Srl.</th>
          <th style="width:61%">Particulars</th>
          <th style="text-align:center;width:12%">Rate</th>
          <th style="text-align:center;width:10%">Quantity</th>
          <th style="text-align:right;width:12%">Sub Total</th>
        </tr>
        </thead>
        <tbody>
          @php $qty = 0; $sub_total = 0; @endphp
           @foreach($salesDetail as $key => $salesDetails)
          <tr>
              <td style="text-align:center">{{ ++$key }}</td>
              <td>
                <b>{{ $salesDetails->item_name }}</b><br>
                {{ $salesDetails->item_note }}
              </td>
              <td style="text-align:right">{{ number_format($salesDetails->sales_price,2) }}</td>
              <td style="text-align:center">{{ $salesDetails->quantity }} Pcs</td>
              <td style="text-align:right">{{ number_format($salesDetails->sales_price * $salesDetails->quantity,2) }}</td>
          </tr>
          @php
           $qty += $salesDetails->quantity;
           $sub_total += $salesDetails->sales_price * $salesDetails->quantity; @endphp 
          @endforeach
          
         <!--  <tr>
              <td style="text-align:center">1</td>
              <td>
                <b>HCL-214-155-2-1-88</b><br>
                DINING CHAIR<br><br>
                Fab: Rabia WR_180 @1100/-yds. CHAIR= 0.33yds x 6pcs x (1100/- -700/-) = 792(ADDISTIONAL FABRICS CHARGE).
                STANDARD
              </td>
              <td style="text-align:right">7,500.00</td>
              <td style="text-align:center">6 Pcs</td>
              <td style="text-align:right">45,000.00</td>
          </tr>
          <tr>
              <td style="text-align:center">2</td>
              <td>
                <b>HCL-215-145-2-1-88</b><br>
                DINING TABLE<br><br>
                STANDARD (FOR 6 CHAIR)
              </td>
              <td style="text-align:right">11,600.00</td>
              <td style="text-align:center">1 Pcs</td>
              <td style="text-align:right">11,600.00</td>
          </tr>
          <tr>
              <td style="text-align:center">3</td>
              <td>
                <b>HCL-237-101-2-2-88</b><br>
                SOLID TOP FOR DINING TABLE<br><br>
                39" x 63" (FOR 6 CHAIR)
              </td>
              <td style="text-align:right">9,500.00</td>
              <td style="text-align:center">1 Pcs</td>
              <td style="text-align:right">9,500.00</td>
          </tr> -->
          <tr>
              <th colspan="2" style="text-align:right"> Total</th>
              <th style="text-align:center"></th>
              <th style="text-align:center">{{$qty}} Pcs</th>
              <th style="text-align:right">{{number_format($sub_total,2)}}</th>
          </tr>

        </tbody>
      </table>
    </div>
    
  </div>

</div>

    <div class="row" style="margin-top: 2%">
      <div class="col-md-12">
        <div class="col-xs-6">
          <div style="font-size: 75%">
            <b>N1: Payment: </b>Must be in Cash Counter<br>
            <!-- <b>N2: Fitting: </b>For Knockdown/Others furniture fitter will go next day after delivery<br>
            <b>N3: Order Change: </b>Set Order can be changed within 4 days but set broken order cannot be changed<br> -->
            <b>N2: Return: </b>No sales return<br>
            @if($salesMasters->remarks!='')
            <strong>Notes:</strong> {{$salesMasters->remarks}}
            @endif
          </div>

          <p style="margin-top: 10px;font-size: 90%">
            <b>In Words: {{ getCurrency($salesMasters->memo_total-$salesMasters->discount) }} Only</b>
          </p>

        </div>
        <div class="col-xs-1">
        </div>
        <div class="col-xs-5">
          <div class="table-responsive">
            <table class="table" style="margin: 0px; padding: 0px;">
              <tr>
                <td style="width:70%;font-size: 75%; margin: 1px; padding: 1px;" >Vat: {{$globalSettings->vat_percent}}%</td>
                <td style="text-align: right;font-size: 75%; margin: 1px; padding: 1px;">{{ number_format($sub_total*$globalSettings->vat_percent/100,2) }}</td>
              </tr>
              @if(count($addLessDetails)>0)
              @foreach($addLessDetails as $data)
              <tr>
                <td style="width:70%;font-size: 75%; margin: 1px; padding: 1px;">@if($data->amount>0) Add: @else Less: @endif{{$data->particular}}:</td>
                <td style="text-align: right;font-size: 75%; margin: 1px; padding: 1px;">{{number_format($data->amount,2)}}</td>
              </tr>
              @endforeach
              @endif
              <tr>
                <td style="width:70%;font-size: 75%; margin: 1px; padding: 1px;">Discount:</td>
                <td style="text-align: right;font-size: 75%; margin: 1px; padding: 1px;">{{number_format($salesMasters->discount,2)}}</td>
              </tr>
              <tr>
                <th style="width:70%;font-size: 75%; margin: 1px; padding: 1px;">Net Amount:</th>
                <th style="text-align: right;font-size: 75%; margin: 1px; padding: 1px;">{{number_format($salesMasters->memo_total-$salesMasters->discount,2)}}</th>
              </tr>
              <tr>
                <th style="width:70%;font-size: 75%; margin: 1px; padding: 1px;">Paid:</th>
                <th style="text-align: right;font-size: 75%; margin: 1px; padding: 1px;">{{number_format($salesMasters->advanced_amount,2)}}</th>
              </tr>
              <tr>
                <th style="width:70%;font-size: 75%; margin: 1px; padding: 1px;">Balance:</th>
                <th style="text-align: right;font-size: 75%; margin: 1px; padding: 1px;">{{number_format($salesMasters->memo_total-$salesMasters->discount-$salesMasters->advanced_amount,2)}}</th>
              </tr>
            </table>
          </div>
        </div>
      </div>
      

    </div>
    
    <div class="row" style="margin-top: 4%">
      <div class="col-xs-12">
        <div class="col-xs-4">
          <div style="text-align:center; font-size: 75%">
            ______________________<br>
              Customer's Signature
          </div>
        </div>
        <div class="col-xs-4" style="text-align:center">
          <div style="text-align:center; font-size: 75%">
             <!-- For {{ $globalSettings->company_name }}: -->
          </div>

        </div>
        <div class="col-xs-4" style="text-align:right">
          <div style="text-align:center; font-size: 75%">
            ______________________<br>
             {{$sold_by->name}}
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

