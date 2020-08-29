@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>STOCK TRANSFER</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Stock Transfer</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    
            <div class="row">
                <div class="col-md-4">
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      Add Transfer
                    </div>
                    <div class="panel-body">
                       <form class="form-horizontal" name="movement_form" action="{{ url('/stock_location/item/movement/store') }}" method="post" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="date">Date</label>
                        <div class="col-sm-10">
                          <input type="text" autocomplete="off" id="datepicker" name="date"  value="{{date('Y-m-d')}}" class="form-control input-sm" required="" />
                          
                         </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email">From</label>
                        <div class="col-sm-10">
                          <select class="form-control select2" name="from" required="" id="from">
                              <option value="">Select</option>
                              @foreach($stock_locations as $st)
                              <option value="{{$st->stock_location_id}}">{{$st->stock_location_name}}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email">To</label>
                        <div class="col-sm-10">
                          <select class="form-control select2" name="to" required="" id="to">
                              <option value="">Select</option>
                              @foreach($stock_locations as $st)
                              <option value="{{$st->stock_location_id}}">{{$st->stock_location_name}}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email" required>Item</label>
                        <div class="col-sm-10">
                          <select class="form-control select2" name="item_id" id="item_id">
                              <option value="">Select</option>
                              @foreach($items as $item)
                              <option value="{{$item->item_id}}">{{$item->item_code}} | {{$item->item_name}}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email">Quantity</label>
                        <div class="col-sm-10">
                          <input type="text" name="quantity" id="quantity" autocomplete="OFF" value="" class="form-control input-sm" required="" />
                          <input type="hidden" id="av_qty"  value=""/>
                          <span id="result" class="text text-danger"></span>
                         </div>
                        </div>
                      
                      
                      <div class="form-group"> 
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" id="submit" class="btn btn-success"><i class="fa fa-download"></i> Save</button>
                        </div>
                      </div>
                      </div>
                     </div>
                    </form>
               @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}}
                        @php
                        Session::forget('success');
                        @endphp
                    </div>
                    @endif
                    @if(Session::has('update'))
                    <div class="alert alert-warning" id="update">
                        {{Session::get('update')}}
                        @php
                        Session::forget('update');
                        @endphp
                    </div>
                    @endif
                    @if(Session::has('delete'))
                    <div class="alert alert-danger" id="delete">
                        {{Session::get('delete')}}
                        @php
                        Session::forget('delete');
                        @endphp
                    </div>
                    @endif     
          </div>
                     
                  
            <div class="col-md-8">
              <div class="panel panel-primary">
                <div class="panel-heading">List Transfers</div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table-bordered" id="purchase_details" width="100%">
                      <thead>
                        <tr>
                            <th height="25">Slr</th>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>From</th>
                            <th>To</th>
                            <!-- <th>User</th> -->
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(isset($stock_transfers))
                            @foreach($stock_transfers as $key => $data)
                            <tr>
                                <td height="25">{{ ++$key }}</td>
                                <td>{{ date('M d, Y', strtotime($data->date)) }}</td>
                                <td>{{ $data->getItemName->item_name }}</td>
                                <td>{{ $data->quantity }}</td>
                                <td>{{ $data->getWareHouseFrom->stock_location_name }}</td>
                                <td>{{ $data->getWareHouseto->stock_location_name }}</td>
                                <!-- <td>{{ $data->getUserName->name }}</td> -->
                                <td>
                                  <form action="{{ url('/stock_transfer/'.$data->id) }}" method="post">
                                      {{ method_field('DELETE') }}
                                      {{ csrf_field() }}
                                      <button class="delete btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');" title="DELETE"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                  </form>
                                </td>
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

<script>
$(document).ready(function () {

    $("#from").change(function() {
        if(parseInt($("#from").val()) == parseInt($("#to").val())){
            alert('Please select a different location');
            $("#from").val('');
        }
    });

    $("#to").change(function() {
        if(parseInt($("#from").val()) == parseInt($("#to").val())){
            alert('Please select a different location');
            $("#to").val('');
        }
    });

    $("#quantity").keyup(function() {
        if(isNaN($("#quantity").val()) || parseInt($("#quantity").val()) > parseInt($("#av_qty").val())){
            alert('Wrong input');
            $("#quantity").val('');
        }
    });

    $("#submit").click(function() {
        if(parseInt($("#quantity").val()) > parseInt($("#av_qty").val())){
            alert("Wrong input");
            $("#quantity").val('');
            $('#quantity').focus();
            event.preventDefault();
        }
    });

    $("#item_id").change(function() {
        var item_id = $("#item_id").val();
        var from = $("#from").val();
        $.ajax({
                url:'{{url('/stock_location/item/quantity/')}}'+"/"+item_id+"/"+from,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $("#loader").show();
                },
                success:function(data) {
                    $('#result').empty();
                    $('#result').text("Available Quantity : "+data.qty);
                    $('#av_qty').val(data.qty);
                    console.log(data);
                },
                complete: function(){
                    $("#loader").hide();
                },
                error: function (data) {
                    $('#Error').text('Error occured.');
                }
            });

    });

    
});

</script>

@endsection