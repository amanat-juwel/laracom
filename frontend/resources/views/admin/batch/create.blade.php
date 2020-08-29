@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
         BATCH
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Batch</li>
        <li class="active">Create New</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                  Create New Account
                  <a href="{{ url('admin/batch/') }}" class="btn btn-default btn-sm pull-right"> List</a>
                </div>
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/batch/store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="col-md-6">
                       
                            <div class="form-group">
                                <label for="">Code</label>
                                <input autocomplete="OFF" type="text"  name="code" placeholder="Code" class="form-control input-sm" />
                            </div>
                            <div class="form-group">
                                <label for="">Select item</label>
                                <select name="item_id" id="account_group_id" class="form-control select2 input-sm">
                                    <option>--Select--</option>
                                    @foreach($items as $data)
                                    <option value="{{$data->item_id}}">{{$data->item_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for=""> Purchase Price</label>
                                <input autocomplete="OFF" type="text"  name="purchase_rate" placeholder="Purchase Rate" class="form-control input-sm" />
                            </div>
                        

                            <div class="form-group pull-right">
                                <button type="submit" name="action" value="save" class="btn btn-success">Save</button>
                                <button type="submit" name="action" value="save_and_close" class="btn btn-warning">Save &amp; Close</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </form>
                </div>
            </div>
            @if(Session::has('success'))
            <div class="alert alert-success" id="success">
                {{Session::get('success')}}
                @php
                Session::forget('success');
                @endphp
            </div>
            @endif
        </div>
    </div>
</section>

<script type="text/javascript">
    
$(document).ready(function() {

    $('#account_type_id').on('change', function(){
        var account_type_id = $(this).val();
     
        if(account_type_id) {
            $.ajax({
                url:'{{url('/account-type/')}}'+"/"+account_type_id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    //alert(data);
                    $('#sub_account_type_id').empty();
                    $('#sub_account_type_id').append('<option>Select Sub-AccountType</option>');
                    $.each(data, function(key, value){
                        $('#sub_account_type_id').append('<option value="'+ data[key]['id'] +'">' + data[key]['name'] + '</option>');
                    });
                },
            });
        } 
        else {
            $('#sub_account_type_id').empty();
        }

    });

    $('#account_group_id').on('change', function(){
        var account_group_id = parseInt($(this).val());
     
        if(account_group_id==2) {
           
           $('.payment-method').css('visibility','visible');
        }
        else{

           $('.payment-method').css('visibility','hidden'); 
        } 

    });

    $('#debit,#credit').on('keyup', function(){
        var debit = parseFloat($('#debit').val());
        var credit = parseFloat($('#credit').val());

        if(debit>0) {
           $('#credit').val(0);
        }
        else if(credit>0){
           $('#debit').val(0);
        } 

    });
});
</script>
@endsection