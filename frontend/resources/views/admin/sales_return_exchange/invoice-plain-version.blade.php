@include('admin.sales.GetCurrencyClass')
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
<body onload="//window.print();">

<!-- Main content -->
<section class="container" style="margin-left: 5%; margin-right:5%; font-size: 100%">

  <div class="row">
      <center>
          <h2>{{$globalSettings->company_name}}</h2>
          {{$globalSettings->address}}<br>
          Mobile: {{$globalSettings->mobile}}<br>
          <strong><u>INVOICE</u></strong>
      </center>
      <div class="col-md-12">
        Date: {{ date("jS \of F Y ") }}<br>
        Invoice No : {{ $salesMasters->sales_master_id }}<br>
        Branch Name: {{ $salesMasters->customer_name }}<br>
        Address: @if(!empty($salesMasters->address)){{ $salesMasters->address }} @endif<br>
        Phone: @if(!empty($salesMasters->mobile_no)){{ $salesMasters->mobile_no }} @endif
      </div>
  </div>
  <p>&nbsp</p>

<div class="row">
  <div class="col-xs-12 table-responsive">
    <table class=" table-striped" width="100%">
      <thead>
      <tr>
        <th style="width:5%">No</th>
        <th style="width:15%">Product Id</th>
        <th style="width:45%">Item</th>
        <th style="text-align:right;width:6%">Qty</th>
        <th style="text-align:right;width:12%">Unit Price</th>
        <th style="text-align:right;width:12%">Amount</th>
      </tr>
      </thead>
      <tbody>
        @php $sub_total = 0 ; $qty = 0; $flag = '';@endphp
        @foreach($salesDetail as $key => $salesDetails)
        @php $flag = $salesDetails->item_name; @endphp
        <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $salesDetails->item_code }}</td>
            <td>Style:{{ $salesDetails->item_name }}&nbsp&nbspSize: {{ $salesDetails->size }}&nbsp&nbspColor: {{ $salesDetails->color_1 }}</td>
            
            <td style="text-align:right">{{ $salesDetails->quantity }}</td>
            <td style="text-align:right">{{ number_format($salesDetails->sales_price,2) }}</td>
            <td style="text-align:right">
              {{ number_format(($salesDetails->sales_price * $salesDetails->quantity)+($salesDetails->sales_price * $salesDetails->quantity) * ($salesDetails->item_vat/100),2) }}
            </td>
        </tr>

        @php $sub_total += $salesDetails->sales_price * $salesDetails->quantity;
              $qty +=  $salesDetails->quantity ;
        @endphp
        @endforeach
      </tbody>
      <tr style="text-align: right;font-weight: bold">
        <td colspan="4">
          <span style="text-decoration:underline;border-bottom: 1px solid #000;">{{$qty}}</span>
        </td>
        <td colspan="2">
          <span style="text-decoration:underline;border-bottom: 1px solid #000;">{{ number_format($sub_total-$salesMasters->discount,2) }}</span>
        </td>
      </tr>
    </table>
    <p class="" style="margin-top: 10px;">
      <b>In Words: </b>{{ getCurrency($sub_total-$salesMasters->discount) }} Tk Only
    </p>
  </div>
  <p>&nbsp</p><p>&nbsp</p>
  <div class="row" style="text-align: center;">
      <div class="col-xs-4">
        Received By
      </div>
      <div class="col-xs-4">
        Checked By
      </div>
      <div class="col-xs-4">
        Issue By
      </div>
  </div>
</div>

</section>
<!-- End Main Content -->
</body>
</html>

