@extends('admin.layouts.template')


@section('template')
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
    @media print {
      .no-print {
        display: none;
      }
      .print{
        display: inline;
      }
    }
</style>

<style type="text/css">
    .double-underline{
        text-decoration:underline;
        border-bottom: 1px solid #000;
    }
    .text-gray{
      color: #328e35 !important;
    }
    .table-striped{
      background-color: #E0E7EB;
    }

    @media print
    {    
        .no-print
        {
            display: none !important;
        }
    }
    h3,p{
        margin: 2px;
        padding: 2px;
    }

</style>
<!-- Content Header -->
<section class="content-header">
    <h1>
        PROFIT & LOSS
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Profit & Loss</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    
    <div class="row">
        <div class="col-md-12 no-print">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/reports/income-statement') }}" id="date_form" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">From</label>
                                <input type="text" autocomplete="OFF" id="datepicker" name="start_date" class="form-control input-sm" @if(isset($start_date))  value="{{ $start_date }}"@endif  required/>
                            </div>
                        </div>    
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label for="">To</label>
                                 <input type="text" autocomplete="OFF" id="datepicker2" name="end_date" class="form-control input-sm" @if(isset($end_date))  value="{{ $end_date }}"@endif required onchange='if(this.value != "") { this.form.submit(); }'/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <br>
                                <input type="submit" class="btn btn-success btn-sm" value"Submit"/>
                            </div>
                        </div>  
                        <div class="col-md-3">
                           <!--  <div class="form-group">
                            <br>
                            @if(isset($start_date))
                            <a class="btn btn-warning btn-xs pull-right" href="{{ url('/report/income-statement-pdf/'.$start_date.'/'.$end_date) }}" target="_blank">Print/Download as PDF</a>     
                            @endif
                            </div> -->
                        </div>  
                    </form>
                </div>
            </div>    
        </div>   
        @if(isset($start_date)) 
        <div class="col-md-12 print">
            <center>
                <h3>{{$globalSettings->company_name}}</h3>
                <p>INCOME STATEMENT</p>
                <p>{{ date('M d, Y',strtotime($start_date )) }} To {{ date('M d, Y',strtotime($end_date )) }}</p>
                <a style="margin-bottom: 10px;" class="btn btn-warning btn-sm no-print" onClick="window.print();"><i class="fa fa-print"></i> Print</a>
            </center>
        </div>

        @if(count($income_transaction)>0) 
        <div class="col-md-12">
            <!-- START CUSTOM TABS -->
              <div class="row">
                <div class="col-md-12">
                  <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active no-print"><a href="#tab_1" data-toggle="tab">Summary</a></li>
                      
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1">
                        <table class="table ">
                          <thead>
                            <tr>
                              <th>ACCOUNTS</th>
                              <th style="text-align: right;">{{ date('M d, Y',strtotime($start_date )) }}<br>To {{ date('M d, Y',strtotime($end_date )) }} </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Sales</td>
                              @php $total_income = $income_transaction; @endphp
                            
                              <td style="text-align: right;">BDT {{number_format($income_transaction,2)}}</td>
                            </tr>
                            <tr>
                              <td>Less: Sales Return</td>
                              <td style="text-align: right;">BDT {{number_format($sales_return,2)}}</td>
                            </tr>
                            <tr>
                              <td><strong>Net Sales</strong></td>
                              
                            
                              <td style="text-align: right;"><strong>BDT {{number_format($income_transaction-$sales_return,2)}}</strong></td>
                            </tr>
                            <tr>
                              <td>Less: Cost of Goods Sold</td>
                              <td style="text-align: right;">BDT {{number_format($cost_of_goods_sold,2)}}</td>
                            </tr>
                            <tr style="background-color: #E0E7EB;">
                              <td><strong>Gross Profit</strong><br><span class="text-gray">As a percentage of Total Income</span></td>
                              <td style="text-align: right;"><strong>BDT {{number_format($total_income-$sales_return-$cost_of_goods_sold,2)}}</strong><br><span class="text-gray">{{number_format((100/$total_income) * ($total_income-$sales_return-$cost_of_goods_sold),0)}}%</span></td>
                            </tr>
                         
                            <tr>
                              <td>Less: Administrative, Financial, Selling & Distribution Expenses</td>
                              @php $total_expense = $administrative_fin_selling_expense; @endphp
                              <td style="text-align: right;">BDT {{number_format($total_expense,2)}}</td>
                            </tr>
                            <tr style="background-color: #E0E7EB;">
                              <td><strong>Net Profit</strong><br><span class="text-gray">As a percentage of Total Income</span></td>
                              <td style="text-align: right;"><strong>BDT {{number_format($total_income-$sales_return-$cost_of_goods_sold-$total_expense,2)}}</strong><br><span class="text-gray">{{number_format((100/$total_income) * ($total_income-$sales_return-$cost_of_goods_sold-$total_expense),0)}}%</span></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <!-- /.tab-pane -->
                     
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
              </div>
              <!-- /.row -->
              <!-- END CUSTOM TABS -->
        </div>
        @endif
        @endif
</section>
<!-- End Main Content -->
@endsection

