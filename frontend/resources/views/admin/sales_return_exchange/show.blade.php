@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SALES RETURN & EXCHANGE DETAILS 
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Return & Exchange</li>
        <li class="active"> Details</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-8">
                    @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                    @endif
                    <div class="">
                        
                        <div class="">

                       <div class="panel panel-primary">
                          <div class="panel-heading">Basic Info 
                        </div>
                          <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for=""> Date: <strong>{{ date('d-M-Y', strtotime($_Master->date)) }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Sales Invoice No: <strong>{{ $_Master->sales_master_id }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer: <strong>{{ $_Master->customer_name }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Total: 
                                            <strong>
                                                @if($_Master->cash_in>0)
                                                @php echo $_Master->cash_in." (Inward)"; @endphp
                                                @elseif($_Master->cash_out>0)
                                                @php echo $_Master->cash_out." (Return)"; @endphp
                                                @else
                                                @php echo 0; @endphp
                                                @endif
                                            </strong>
                                        </label>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Payment Method: 
                                                <strong>
                                                   @if(strpos(strtolower("$_Master->payment_method"), 'cash') !== false)
                                                   Cash
                                                   @elseif(strpos(strtolower("$_Master->payment_method"), 'card') !== false)
                                                   Card
                                                   @else
                                                   {{ $_Master->payment_method }}
                                                   @endif
                                                </strong>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                          </div>
                        </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table-responsive">
                                        <div class="panel panel-primary">
                                          <div class="panel-heading">
                                            Item Details 
                                            </div>
                      
                                          <div class="panel-body">
                                            <table class="table-bordered" id="" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th height="25">Sl</th>
                                                        <th>Item </th>
                                                        <th>Quantity</th>
                                                        <th>Rate</th>
                                                        <th>Total</th>
                                                        <th>Type</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($_Details as $key => $data)
                                                    <tr>
                                                        <td height="25">{{ ++$key }}</td>
                                                        <td>{{ $data->item_code }} | {{ $data->item_name }}</td>
                                                        <td>{{ $data->qty }}</td>
                                                        <td>{{ number_format($data->rate,2) }}</td>
                                                        <td>{{ number_format($data->rate * $data->qty,2)}}</td>
                                                        <td>{{ $data->type }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>          
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->


@endsection