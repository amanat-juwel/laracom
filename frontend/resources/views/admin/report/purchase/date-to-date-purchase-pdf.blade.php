<!DOCTYPE html>
<html lang="en">
<head>
  <title>Date to Date Purchase Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<style type="text/css" media="print">
    @page { 
    size: auto;
    margin: 6mm 0 10mm 0;
    }
    body {
        margin:0;
        padding:0;
    }

</style>
<body onload="window.print();">

<!-- Main content -->
<section class="content">
    <div class="">
        <div style="margin-right: 3%; margin-left: 3%;margin-top: 3%">
            <div class="row">
                <center>
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    <p>Purchase Report</p>
                    <p><b>From: </b>{{date('d-m-Y', strtotime($start_date))}}, <b>To: </b>{{date('d-m-Y', strtotime($end_date))}}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" id="" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Sl</th>
                                    <th style="text-align: center;">Date</th>
                                    <th style="text-align: center;">Item</th>
                                    <th style="text-align: center;">Batch Code</th>
                                    <th style="text-align: center;">Particulars</th>
                                    <th style="text-align: center;">Quantity</th>
                                    <th style="text-align: center;"> Rate</th>
                                     <th style="text-align: center;"> Amount</th>
                                </tr>
                            </thead>

                            <tbody id="myTable">
                                @php $qty = 0; $total_purchase_rate = 0;$total_sales_rate = 0; @endphp

                                @foreach($purchase_details as $key => $data)
                                <tr>
                                    <td height="25">{{ ++$key }}</td>
                                    <td style="text-align: center;">{{ $data->purchase_date }} </td>
                                    <td style="text-align: center;">{{ $data->item_name }} </td>
                                    <td style="text-align: center;">{{ $data->code }} </td>
                                    <td style="text-align: center;">{{ $data->particulars }}</td>
                                    <td style="text-align: center;">{{ $data->stock_in }}</td>
                                    <td style="text-align: center;">{{ number_format($data->purchase_rate,2) }}</td>
                                    <td style="text-align: center;">{{ number_format($data->stock_in*$data->purchase_rate,2) }}</td>
                                  
                                </tr>
                                @php $qty += $data->stock_in; $total_purchase_rate += ($data->purchase_rate * $data->stock_in);
                                @endphp
                                @endforeach
                                <tr>
                                    <th>TOTAL</th>
                                    <th colspan="4"></th>
                                    <th style="text-align: center;">{{$qty}}</th>
                                    <th></th>
                                    <th style="text-align: center;">{{number_format($total_purchase_rate,2)}}</th>
                                   
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</section>
<!-- End Main Content -->
</body>
</html>