@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Profit Details
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Profit Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
            <form class="form" action="{{ url('report/date-to-date-profit-by-item-report') }}" id="date_form" method="post">
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
                <div class="col-md-3">
                    <div class="form-group">
                         <br>
                         <input class="btn btn-success btn-sm" type="submit" value="Submit"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                    <br>
                    @if(isset($start_date))
                    <a class="btn btn-warning btn-xs pull-right" href="{{ url('/report/date-to-date-profit-by-item-report-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                    @endif
                    </div>
                </div>  
            </form>    
                <div class="col-md-12">
                <!--
                @if(isset($start_date))
                <p align="center"><b>From:</b> {{ $start_date }} <b>To:</b> {{ $end_date }}</p>
                @endif
                -->
                <div class="table-responsive">
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
                    </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection

<script>
    $("#submit").click(function() {   //button id
        var myForm = $("#dateform");  //form id
        myForm.submit(function(e){
        e.preventDefault();
        var formData = myForm.serialize();
            $.ajax({
                url:'{{url('report/date-to-date-sales-report')}}',
                type:'post',
                data:formData,
                success:function(data){
                //  alert('Successfully Retrived');
                //  $('#start_date,#end_date').val('');
                },
                error: function (data) {
                    alert('Something Went Wrong');
                }
            });
        });
    });    
</script>