
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Inventory Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
</head>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 6mm 0 10mm 0;  /* this affects the margin in the printer settings */

    }
</style>
<style type="text/css">
    th,td{
        padding: 2px;
    }
    p{
      margin: 0px;
    }
</style>
<style>
@media print
{
  table { page-break-after:auto }
  tr    { page-break-inside:avoid; page-break-after:auto }
  td    { page-break-inside:avoid; page-break-after:auto }
  thead { display:table-header-group }
  tfoot { display:table-footer-group }
}
</style>
<style type="text/css">
  h2{
    margin-bottom: 0px;
  }

  .border-top{
    border-top: 1px solid black;
  }

  table.table-bordered{
    border:1px solid black;
    margin-top:20px;
  }
  table.table-bordered > thead > tr > th{
    border:1px solid black;
  }
  table.table-bordered > tbody > tr > td{
    border:1px solid black;
  }

</style>
<body onload="window.print();">

<!-- Main content -->
<section class="content" >
    <div class="">
        <div class="">
            <div class="row" style="margin-right: 3%; margin-left: 3%;margin-top: 3%">
                <center>
                    <h3>{{$globalSettings->company_name}}</h3>
                    {!!$globalSettings->address!!}
                    <p><b>Inventory Report</b></p>
                    <p><b>From: </b>{{date('F j,Y', strtotime($start_date))}}, <b>To: </b>{{date('F j,Y', strtotime($end_date))}}</p><br>
                </center>
                <div class="col-md-12">
                    <table  class="table-striped" border="1" width="100%">
                          <thead>
                              <tr class="text-center font-bold">
                                  <td rowspan="2">Srl</td>
                                  <td rowspan="2">Item</td>
                                  <td colspan="2" class="bg-1">Opening Inventory</td>
                                  <td colspan="4" class="bg-2">Transaction Inventory</td>
                                  <td colspan="2" class="bg-3" >Closing Inventory</td>
                              </tr>
                              <tr class="text-center font-bold">
                                  <td class="bg-1">Stock</td>
                                  <td class="bg-1">Amount</td>
                                  <td class="bg-2">Stock In</td>
                                  <td class="bg-2">Amount</td>
                                  <td class="bg-2">Stock Out</td>
                                  <td class="bg-2">Amount</td>
                                  <td class="bg-3">Stock</td>
                                  <td class="bg-3">Amount</td>
                              </tr>
                          </thead>
                          <tbody id="myTable">
                          @for ($i=0; $i < count($inventory); $i++) 
                              <tr>
                                  <td>{{$i+1}}</td>
                                  <td>{{$inventory[$i]['item_name']}}</td>
                                  <td class="qty-center bg-1">{{$inventory[$i]['opening_qty']}}</td>
                                  <td class="amount bg-1">{{$inventory[$i]['opening_amount']}}</td>
                                  <td class="qty-center bg-2">{{$inventory[$i]['transaction_qty_in']}}</td>
                                  <td class="amount bg-2">{{$inventory[$i]['transaction_amount_in']}}</td>
                                  <td class="qty-center bg-2">{{$inventory[$i]['transaction_qty_out']}}</td>
                                  <td class="amount bg-2">{{$inventory[$i]['transaction_amount_out']}}</td>
                                  <td class="qty-center bg-3">{{$inventory[$i]['closing_qty']}}</td>
                                  <td class="amount bg-3">{{$inventory[$i]['closing_amount']}}</td>
                              </tr>
                          @endfor 

                          <tr class="font-bold">
                              <td class="text-center" colspan="2">TOTAL</td>
                              <td class="qty-center bg-1">{{$op_inventory_total_qty}}</td>
                              <td class="amount bg-1">{{number_format($op_inventory_total_amount,2)}}</td>
                              <td class="qty-center bg-2">{{$stock_in_today}}</td>
                              <td class="amount bg-2">{{number_format($stock_in_total,2)}}</td>
                              <td class="qty-center bg-2">{{$stock_out_today}}</td>
                              <td class="amount bg-2">{{number_format($stock_out_total,2)}}</td>
                              <td class="qty-center bg-3">{{$closing_inventory_total_qty}}</td>
                              <td class="amount bg-3">{{number_format($closing_inventory_total_amount,2)}}</td>
                          </tr>
                          </tbody>
                      </table>
                </div>
                                
            </div>
        </div>
    </div>

</section>
<!-- End Main Content -->
</body>
</html>