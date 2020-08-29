@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')

<section class="content-header">
    <h1>INVENTORY </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Stock</li>
        <li class="active">Inventory</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content" style="">
    <div class="row">
        <div class="col-md-12" id="printPageButton">    
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/finished-product/inventory') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Category</label>
                                <select name="r_m_cata_id" class="form-control input-sm" >
                                    <option value="0">All Categories</option>
                                    @foreach($categories as $data)
                                    <option value="{{$data->cata_id}}" @if(isset($cata_id)) @if($data->cata_id==$cata_id) selected @endif @endif>{{$data->cata_name}}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Start Date</label>
                                <input type="text" autocomplete="OFF" id="datepicker" name="start_date" class="form-control input-sm" @if(isset($start_date)) value="{{ $start_date }}"@endif  required />
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">End Date</label>
                                <input type="text" autocomplete="OFF" id="datepicker2" name="end_date" class="form-control input-sm" @if(isset($end_date)) value="{{ $end_date }}"@endif  required onchange='if(this.value != "") { this.form.submit(); }'/>
                            </div>
                        </div>    
                        <div class="col-md-3">
                            &nbsp<br>
                            <button  type="submit" class="btn btn-info btn-sm " style="">Submit</button>
                        </div>  
                    </form>
                </div>
            </div>        
        </div>
        
        @if(isset($start_date))
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading" id="printPageButton">
                    List Inventory
                   
                    
            
                </div>

                <div class="panel-body">
                    <div class="table-responsive" style="">
                        <!-- <input class="form-control" id="myInput" type="text" placeholder="Search..">
                        <br> -->
                        <table  class="table-striped" border="1" width="100%" id="dataTable">
                            <thead>
                                <tr class="text-center font-bold">
                                    <td rowspan="2">Srl</td>
                                    <td rowspan="2">Item</td>
                                    <td colspan="3" class="bg-1">Opening </td>
                                    <td colspan="6" class="bg-2">Transit </td>
                                    <td colspan="3" class="bg-3" >Closing </td>
                                </tr>
                                <tr class="text-center font-bold">
                                    <td class="bg-1">Stock</td>
                                    <td class="bg-1">Rate</td>
                                    <td class="bg-1">Amount</td>

                                    <td class="bg-2">Receive Qty</td>
                                    <td class="bg-2">Rate</td>
                                    <td class="bg-2">Amount</td>
                                    <td class="bg-2">Issue Qty</td>
                                    <td class="bg-2">Rate</td>
                                    <td class="bg-2">Amount</td>

                                    <td class="bg-3">Stock</td>
                                    <td class="bg-3">Rate</td>
                                    <td class="bg-3">Amount</td>
                                </tr>
                            </thead>
                            <tbody id="">
                            @php
                                $opening_total_quantity = 0;
                                $opening_total_amount = 0;

                                $transit_in_total_quantity = 0;
                                $transit_in_total_amount = 0;
                                $transit_out_total_quantity = 0;
                                $transit_out_total_amount = 0;

                                $closing_total_quantity = 0;
                                $closing_total_amount = 0;
                            @endphp
                            @for ($i=0; $i < count($opening); $i++) 
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td>{{$opening[$i]['name']}}</td>
                                    <td class="qty-center bg-1">{{number_format($opening[$i]['quantity'],2)}}</td>
                                    <td class="amount bg-1">
                                        @if($opening[$i]['quantity']>0)
                                        {{number_format($opening[$i]['amount']/$opening[$i]['quantity'],2)}}
                                        @endif
                                    </td>
                                    <td class="amount bg-1">{{number_format($opening[$i]['amount'],2)}}</td>

                                    <td class="qty-center bg-2">{{number_format($transit_in[$i]['quantity'],2)}}</td>
                                    <td class="amount bg-2">
                                        @if($transit_in[$i]['quantity']>0)
                                        {{number_format($transit_in[$i]['amount']/$transit_in[$i]['quantity'],2)}}
                                        @endif
                                    </td>
                                    <td class="amount bg-2">{{number_format($transit_in[$i]['amount'],2)}}</td>

                                    <td class="qty-center bg-2">{{abs($transit_out[$i]['quantity'])}}</td>
                                    <td class="amount bg-2">
                                        @if(abs($transit_out[$i]['quantity'])>0)
                                        {{number_format(abs($transit_out[$i]['amount'])/abs($transit_out[$i]['quantity']),2)}}
                                        @endif
                                    </td>
                                    <td class="amount bg-2">{{number_format(abs($transit_out[$i]['amount']),2)}}</td>

                                    <td class="qty-center bg-3">{{number_format($closing[$i]['quantity'],2)}}</td>
                                    <td class="amount bg-3">
                                        @if($closing[$i]['quantity']>0)
                                        {{number_format(($opening[$i]['amount']+$transit_in[$i]['amount']-abs($transit_out[$i]['amount']))/$closing[$i]['quantity'],2)}}
                                        @endif
                                    </td>
                                    <td class="amount bg-3">{{number_format($opening[$i]['amount']+$transit_in[$i]['amount']-abs($transit_out[$i]['amount']),2)}}</td>
                                </tr>
                                @php
                                    $opening_total_quantity += $opening[$i]['quantity'];
                                    $opening_total_amount += $opening[$i]['amount'];

                                    $transit_in_total_quantity += $transit_in[$i]['quantity'];
                                    $transit_in_total_amount += $transit_in[$i]['amount'];
                                    $transit_out_total_quantity += abs($transit_out[$i]['quantity']);
                                    $transit_out_total_amount += abs($transit_out[$i]['amount']);

                                    $closing_total_quantity += $closing[$i]['quantity'];
                                    $closing_total_amount += ($opening[$i]['amount']+$transit_in[$i]['amount']-abs($transit_out[$i]['amount']));
                                @endphp
                            @endfor 

                                <tr class="font-bold">
                                    <td class="text-center" colspan="">{{$i+1}}</td>
                                    <td class="text-center" colspan="">TOTAL</td>
                                    <td class="qty-center bg-1">{{number_format($opening_total_quantity,2)}}</td>
                                    <td class="text-center" colspan="">---</td>
                                    <td class="amount bg-1">{{number_format($opening_total_amount,2)}}</td>
                                    <td class="qty-center bg-2">{{number_format($transit_in_total_quantity,2)}}</td>
                                    <td class="text-center" colspan="">---</td>
                                    <td class="amount bg-2">{{number_format($transit_in_total_amount,2)}}</td>
                                    <td class="qty-center bg-2">{{$transit_out_total_quantity}}</td>
                                    <td class="text-center" colspan="">---</td>
                                    <td class="amount bg-2">{{number_format($transit_out_total_amount,2)}}</td>
                                    <td class="qty-center bg-3">{{number_format($closing_total_quantity,2)}}</td>
                                    <td class="text-center" colspan="">---</td>
                                    <td class="amount bg-3">{{number_format($closing_total_amount,2)}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

@endsection


@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#dataTable').DataTable( {
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print',
            // {
              //     extend: 'copy',
              //     exportOptions: {
              //          columns: [0,1,2,3,4,5]
              //      }

        ]
    } );
} );
</script>
@endsection

