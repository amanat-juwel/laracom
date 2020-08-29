@extends('admin.layouts.template')

@section('title')
Stock Register
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<style type="text/css">
    th{
        text-align: center;
        padding: 2px;
    }
    td{
        padding: 2px;
    }
</style>
<section class="content-header">
    <h1>
        STOCK REGISTER
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Stock Register</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content" style="">
    <div class="row">
        <div class="col-md-12" id="printPageButton">    
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/finished-product/stock-register') }}" name="myForm" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Item</label>
                                <select name="item_id" class="form-control select2" id="" required="" > 
                                    <option value="">---Select---</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->item_id }}" data-price="">{{ $item->item_name }} | {{ $item->item_code }} </option>
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
                   
                    <a class="btn btn-default btn-xs pull-right" href="{{ url('admin/report/finished-product/stock-register-print/'.$item_id.'/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>
            
                </div>

                <div class="panel-body">
                    <div class="table-responsive" style="">
                        <table  class="table-striped" border="1" width="100%" id="dataTable">
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
        @endif
    </div>
</section>
<!-- End Main Content -->

<script type="text/javascript">
    @if(isset($item_id))
         document.forms['myForm'].elements['item_id'].value="{{ $item_id }}"
    @endif
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
            'copy', 'csv', 'excel', 
            

        ]
    } );
} );
</script>
@endsection


