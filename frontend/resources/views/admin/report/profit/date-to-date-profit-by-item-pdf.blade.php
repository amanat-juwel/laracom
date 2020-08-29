<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profit by Item Report</title>
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
<body onload="window.print();">

<!-- Main content -->
<section class="content">
    <div class="" style="margin: 5%">
        <div class="">
            <div class="row">
                <center>
                    <h4>{{ $globalSettings->company_name }}</h4>
                    <p>Profit by Item </p>
                    <p><b>From : </b>{{ $start_date }} , <b>To :</b> {{ $end_date }}</p>
                </center>
                <div class="col-md-12">
                    <div class="">
                        @if(isset($date_to_date_sales))
                        <table class="table table-bordered" width="100%" id="">
                            <thead>
                                <tr>
                                    <th height="">Sl No.</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th class="text-purple">Costing Rate</th>
                                    <th class="text-purple">Costing Amount</th>
                                    <th class="text-orange">Sales Rate</th>
                                    <th class="text-orange">Sales Amount</th>
                                    <th class="text-green">Profit</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $profit = 0; @endphp
                               
                                    @foreach($date_to_date_sales as $key => $data)
                                        <tr>
                                            <td height="">{{ ++$key }}</td>
                                            <td>{{ $data->item_name }}</td>
                                            <td>{{ $data->quantity }}</td>
                                            <td class="text-purple">{{ $data->costing_rate }}</td>
                                            <td class="text-purple">{{ $data->quantity * $data->costing_rate }}</td>
                                            <td class="text-orange">{{ $data->sales_price }}</td>
                                            <td class="text-orange">{{ $data->quantity * $data->sales_price }}</td>
                                            <td class="text-green">{{ ($data->quantity * $data->sales_price) - ($data->quantity * $data->costing_rate) }}</td>
                                        </tr>
                                    @php $profit += (($data->quantity * $data->sales_price) - ($data->quantity * $data->costing_rate)); @endphp
                                    @endforeach
                                    <tr>
                                        <th colspan="7" class="amount">TOTAL PROFIT</th>
                                        <th class="text-green">{{ $profit }}</th>
                                    </tr>
                            </tbody>
                        </table>
                        
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- End Main Content -->
</body>
</html>