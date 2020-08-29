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

<section class="content-header">
    <h1>
        INVOICE LEDGER
        
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

<div style="margin-left: 5%; margin-right: 5%; margin-top:3%">
<div class="row">
  <div class="col-xs-12">
    <div class="col-xs-3">
      <img src="{{ asset($globalSettings->logo) }}" height="55" width="210" style="float:left">
    </div>

    <div class="col-xs-6" style="text-align: center;">
      <!-- <img src="{{ asset('public/image/invoice.png') }}" height="85" width="85" style="float:right"> -->
      <div style="font-size: 75%">
        <h4 style="margin: 0px; padding: 0px;"><strong>{{ strToUpper($globalSettings->company_name) }}</strong></h4>
        {!!$globalSettings->address!!}<br>
        @if($globalSettings->website != '')
        Website: {{$globalSettings->website}}<br>
        @endif
        Mobile: {{$globalSettings->mobile}}, Tel: {{$globalSettings->phone}}<br>
      </div>
    </div>

    <div class="col-xs-3">

    </div>

  </div>

</div>

<div class="row" style="margin-top: 3%">
  <div class="col-xs-12">
    <div class="col-xs-5">
      <div style="font-size: 75%">
        Customer ID: {{ $globalSettings->invoice_prefix."-".str_pad($sales_master->customer_code, 8, '0', STR_PAD_LEFT) }}<br>
        Name: {{ $sales_master->customer_name }}<br>
        Address: @if(!empty($sales_master->address)){{ $sales_master->address }}     @endif<br>
        Contact: @if(!empty($sales_master->mobile_no)){{ $sales_master->mobile_no }}  @endif
      </div>
    </div>
    <div class="col-xs-2" style="text-align:center">
      <h5><strong>INVOICE LEDGER</strong></h5>
    </div>
    <div class="col-xs-5" style="text-align:right">
      <div style="font-size: 75%">
        Invoice No: {{ $globalSettings->invoice_prefix."-BI-".str_pad($sales_master->sales_master_id, 8, '0', STR_PAD_LEFT) }}<br>
        @foreach($customer_ledgers as $data)
          @php $date = $data->transaction_date; @endphp
        @endforeach
        Date: {{ date('F j, Y',strtotime($date)) }}<br>

      </div>
  </div>

  </div>

</div>
<!-- Table row -->

<!-- <section class="invoice-bg">
<div class="row">
  <div class="col-md-12">
      <img src="{{ asset('public/image/invoice-bg.png') }}">
  </div>
</div>     
</section> -->
<!-- 
<div class="row">
  <div class="col-xs-12">
  <div class="col-xs-12 table-responsive">
    <h4><u>Invoice Ledger</u></h4>
    <table class=" table-bordered" width="100%">
            <tr>
              <th style="width: 25%;border-color:black;" height="25">Account</th>
              <th style="width: 45%;border-color:black;">Particular</th>
              <th style="width: 15%;border-color:black;">Debit</th>
              <th style="width: 15%;border-color:black;">Credit</th>
            </tr>

              @if(count($transactions)>0)
                
                <tr>
                  <td>
                    {{$transactions->bank_name}} AC
                  </td>
                  <td>{{$transactions->transaction_description}} </td>
                  <td style="text-align: right;">{{ number_format($transactions->deposit,2) }} </td>
                  <td style="text-align: right;">{{ number_format($transactions->expense,2) }} </td>
                </tr>
                
              @endif

              @if(count($customer_ledgers)>0)
                @foreach($customer_ledgers as $data)
                <tr>
                  <td>
                    {{$data->customer_name}} AC
                  </td>
                  <td>@if($data->tran_ref_name=="PreviousDuePaid") Re-Advanced @else {{$data->tran_ref_name}} @endif </td>
                  <td style="text-align: right;">{{ number_format($data->debit,2) }} </td>
                  <td style="text-align: right;">{{ number_format($data->credit,2) }} </td>
                </tr>
                @endforeach
              @endif
              
          </table>
  </div>
  </div>

</div> -->

    <div class="row" style="margin-top: 3%">
      <div class="col-xs-12">
      
          <div class="col-xs-12">
            <h4><u>Payments</u></h4>
            <table class="table-bordered" id="" width="100%">
                <thead>
                    <tr>
                        <th height="25">Sl</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>
                  @php $total =0 ; @endphp
                    @foreach($paymentHistory as $key => $data)
                    <tr>
                        <td height="25">{{ ++$key }}</td>
                        <td>{{ date('F j, Y',strtotime($data->transaction_date)) }}</td>
                        <td>@if($data->tran_ref_name=="AdvancedPaid") Advanced @elseif($data->tran_ref_name=="PreviousDuePaid") Re-Advanced @else {{$data->tran_ref_name}} @endif</td>
                        <td style="text-align: right;">{{ number_format($data->credit,2) }}</td>
                    </tr>
                    @php $total += $data->credit ; @endphp
                    @endforeach
                    <tr>
                        <th height="25" colspan="3" style="text-align: center;">Total</th>
                        <th style="text-align: right;">{{ number_format($total,2) }}</th>
                    </tr>
                </tbody>
            </table>
            
            <h4 style="margin-top: 3%"><u>Invoice Status</u></h4>
            <table class="table-bordered" id="" width="100%">
                <thead>
                    <tr>
                        <th height="25">Invoice Total</th>
                        <th>Discount</th>
                        <th>Paid</th>
                        <th>Due</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td height="25" style="width: 25%">{{ number_format($sales_master->memo_total,2) }}</td>
                        <td style="width: 25%">{{ number_format($sales_master->discount,2) }}</td>
                        <td style="width: 25%">{{ number_format($sales_master->advanced_amount,2) }}</td>
                        <td style="width: 25%">{{ number_format($sales_master->memo_total-$sales_master->discount-$sales_master->advanced_amount,2) }}</td>
                    </tr>
                </tbody>
            </table>
          </div>
      
      <div class="col-xs-6">

        <div class="table-responsive">
          <table class="table">
                   
          </table>
        </div>
      </div>
      
    </div>
  </div>




</div>
</section>
</section>



<script>
    window.print();
</script>
@endsection

