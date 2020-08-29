<!DOCTYPE html>
<html lang="en">
<head>
  <title>Stock Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
<section class="container" style="margin:5%">
    <div class="">
        <div class="">
            <div class="row">
                <center>
                    <h4>{{$globalSettings->company_name}}</h4>
                    <p>Stock Report</p>
                    <p><b>Print Date: </b><?php echo date("F j, Y");?></p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%" height="25">Srl</th>
                                    <th style="width:30%">Item Name</th>
                                    <th style="width:15%">Catagory Name</th>
                                    <th style="width:15%">Brand Name</th>
                                    <th style="width:10%">Stock</th>

                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($item))
                                    @foreach($item as $key => $items)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $items->item_name }}</td>
                                        <td>{{ $items->cata_name }}</td>
                                        <td>{{ $items->brand_name }}</td>
                                        <td class="@if($items->opening_stock_qty+$items->stock_in-$items->stock_out <= 0) btn-danger 
                                            @elseif($items->opening_stock_qty+$items->stock_in-$items->stock_out > 0 && $items->opening_stock_qty+$items->stock_in-$items->stock_out <= 5 ) btn-warning @else btn-success @endif">{{ $items->opening_stock_qty+$items->stock_in-$items->stock_out  }}</td>

                                    </tr>
                                    @endforeach
                                @endif
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