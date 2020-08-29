@extends('admin.layouts.template')

@section('title')
Expense Report
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>EXPENSE REPORT</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Expenses Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">    
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/report/date-to-date-expenses-report') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">From</label>
                                <input type="text" autocomplete="OFF" id="datepicker" name="start_date" class="form-control input-sm" @if(isset($start_date)) value="{{ $start_date }}"@endif  required/>
                            </div>
                        </div>    
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label for="">To</label>
                                 <input type="text" autocomplete="OFF" id="datepicker2" name="end_date" class="form-control input-sm" @if(isset($end_date)) value="{{ $end_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                 <br>
                                 <input type="submit"  class="btn btn-success btn-sm" value="Submit" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <br>
                            @if(isset($start_date))
                            <a class="btn btn-warning btn-xs pull-right" href="{{ url('admin/report/date-to-date-expenses-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                            @endif
                            </div>
                        </div>  
                    </form>
                </div>
            </div>        
        </div>
        @if(isset($date_to_date_expenses))
        <div class="col-md-12">
                <!--
                @if(isset($start_date))
                <p align="center"><b>From:</b> {{ $start_date }} <b>To:</b> {{ $end_date }}</p>
                @endif
                -->
                <div class="panel panel-primary">
                <div class="panel-heading">
                    List Expenses
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table-bordered" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th style="width: 80%;">Account</th>
                                    <th style="width: 20%;">Amount</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php $total_balance = 0; @endphp
                                @foreach($date_to_date_expenses as $data)
                                <tr>
                                    <td>{{ $data->bank_name }}</td>
                                    <td class="amount">{{ number_format($data->balance,2) }}</td>
                                </tr>
                                @php $total_balance += $data->balance; @endphp
                                @endforeach

                                <tr>
                                    <td class="text-bold">TOTAL</td>
                                    <td class="amount text-bold">{{ number_format($total_balance,2) }}</td>
                                </tr>
                            </tbody>
                        </table>   
                       
                    </div>
            </div>
        </div>
    </div>
    @endif
</section>
<!-- End Main Content -->
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