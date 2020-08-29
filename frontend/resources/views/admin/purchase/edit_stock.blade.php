@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-4">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form" name="editForm" action="{{ url('/purchase/stock/update') }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="stock_id" value="{{ $stock->stock_id }}">
                                <div class="form-group">
                                    <label for="">Item</label>
                                    <select name="item_id" id="item_id" class="form-control select2"  required> 
                                        <option value="">---Select---</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Particulars</label>
                                    <input type="text" autocomplete="OFF" name="particulars" value="{{ $stock->particulars }}" class="form-control" />
                                    @if($errors->has('particulars'))
                                        <span class="text-danger">{{ $errors->first('particulars')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Quantity</label>
                                    <input type="number" autocomplete="OFF" name="stock_in" value="{{ $stock->stock_in }}" class="form-control" required/>
                                    @if($errors->has('stock_in'))
                                        <span class="text-danger">{{ $errors->first('stock_in')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Purchase Rate</label>
                                    <input type="text" autocomplete="OFF" name="purchase_rate" value="{{ $batch->purchase_rate }}" class="form-control" required/>
                                    @if($errors->has('purchase_rate'))
                                        <span class="text-danger">{{ $errors->first('purchase_rate')}}</span>
                                    @endif
                                </div>
                                                     
                                <div class="form-group">
                                    <input type="submit" class="btn btn-warning" value="Update"/>
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
    document.forms['editForm'].elements['item_id'].value="{{ $stock->item_id }}"
</script>
</div>
@endsection
