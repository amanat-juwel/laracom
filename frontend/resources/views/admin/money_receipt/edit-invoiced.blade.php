@extends('admin.layouts.template')



@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>EDIT : MONEY RECEIPT</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit : MR</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-5">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form" name="editForm" action="{{url('admin/money-receipt/'.$money_receipt->mr_id)}}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Date</label>
                                    <input type="text" autocomplete="OFF" id="datepicker" value="{{$money_receipt->date}}" name="date" id="" class=" form-control input-sm" required/>
                                </div>
                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <input autocomplete="OFF" type="text" name="amount" id="amount" value="{{$money_receipt->amount}}" class="form-control input-sm " />
                                </div>
                                <div class="form-group">
                                    <label for="">Payment Method*</label>
                                    <select name="bank_account_id" id="bank_account_id" class="form-control input-sm " >
                                        <option value="">--- Select ---</option>
                                        @foreach($accounts as $key => $account)
                                        <option value="{{ $account->bank_account_id }}" @if($account->bank_account_id == $money_receipt->payment_method) selected @endif>{{ $account->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>                          
                                <div class="form-group">
                                    <input type="hidden" name="type" id="" value="invoiced"/>
                                    <input type="submit" class="btn btn-warning" value="Save Changes"/>
                                </div>
                            </form>
                                               
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>
<script>
    // document.forms['editForm'].elements['item_id'].value=""
</script>
</div>
@endsection
