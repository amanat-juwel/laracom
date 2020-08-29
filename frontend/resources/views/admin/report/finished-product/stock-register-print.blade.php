<!DOCTYPE html>
<html lang="en">
<head>
  <title>Stock Register</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
</head>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
<style type="text/css">
    th{
        text-align: center;
        padding: 2px;
    }
    td{
        padding: 2px;
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
                    <p><b>Stock Register: </b> {{$item->item_name}} @if(isset($item->item_code))<b>item_code: </b> {{$item->item_code}} @endif</p>
                    <p><b>From: </b>{{date('F j,Y', strtotime($start_date))}}, <b>To: </b>{{date('F j,Y', strtotime($end_date))}}</p>
                </center>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table  class="table-striped" border="1" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Op. Bal.</th>
                                    <th>Received</th>
                                    <th>Total</th>
                                    <th>Issue</th>
                                   <!--  <th>Bill/Rq No.</th> -->
                                    <th>Balance</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $bal = $opening_stock;  @endphp
                            @if(count($item_registers)>0)
                            @foreach($item_registers as $key => $data)
                                <tr>
                                    <td class="text-center">{{ date('d-m-Y',strtotime($data->date)) }}</td>
                                    <td>{{$data->particulars}}</td>
                                    <td class="amount">{{number_format($bal,2)}}</td>
                                    <td class="amount">{{number_format($data->stock_in,2)}}</td>
                                    <td class="amount">{{number_format($data->stock_in+$bal,2)}}</td>
                                    @php $bal += $data->stock_in;  @endphp
                                    <td class="amount">{{number_format($data->stock_out,2)}}</td>
                                    <!-- <td class="text-center">{{$data->stock_master_id}}</td> -->
                                    @php $bal -= $data->stock_out;  @endphp
                                    <td class="amount">{{number_format($bal,2)}}</td>
                                    <td class="text-center">@if(isset($data->rate)) {{$data->rate}}/= @endif</td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td class="text-center">{{ date('d-m-Y',strtotime($start_date)) }}</td>
                                    <td>Begining Balance</td>
                                    <td class="amount">{{number_format($bal,2)}}</td>
                                    <td class="amount"></td>
                                    <td class="amount">{{number_format($bal,2)}}</td>
                                    <td class="amount"></td>
                                    <td class="text-center"></td>
                                    <td class="amount">{{number_format($bal,2)}}</td>
                                    <td class="text-center"></td>
                                </tr>
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