@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>STOCK LOOKUP</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Stock Lookup</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    
            <div class="row">
                <div class="col-md-6">
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      Lookup
                    </div>
                    <div class="panel-body">
                       <form class="form-horizontal" name="lookup_form" action="" method="post" enctype="multipart/form-data">
                      {{ csrf_field() }}

                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email" required>Item</label>
                        <div class="col-sm-10">
                          <select class="form-control select2" name="item_id" id="item_id">
                              <option value="">Select</option>
                              @foreach($items as $item)
                              <option value="{{$item->item_id}}">{{$item->item_name}} | {{$item->item_code}}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-sm-2" for="email" required>&nbsp</label>
                        <div class="col-sm-10">
                          <span id="result" class="text text-success"></span>
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
                     
            

        </div>

</section>
<!-- End Main Content -->

<script>
$(document).ready(function () {


    $("#item_id").change(function() {
        var item_id = $("#item_id").val();
        $.ajax({
                url:'{{url('/stock_location/item/lookup/')}}'+"/"+item_id,
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