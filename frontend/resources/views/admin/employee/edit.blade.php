@extends('layouts.template')

@if(Auth::user()->role == 'admin')
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
    <div class="col-md-6">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                        @foreach($items as $items)
                            <form class="form" action="{{ url('/item/'.$items->item_id) }}" method="post" enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="item_id" value="{{ $id }}">

                                <div class="form-group">
                                    <label for="">Item Name</label>
                                    <input type="text" name="item_name" class="form-control" value="{{ $items->item_name }}" required/>
                                    @if($errors->has('item_name'))
                                        <span class="text-danger">{{ $errors->first('item_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Category</label>
                                    <select name="cata_id" id="" class="form-control" required> 
                                        <option value="">---Select---</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->cata_id }}" {{ $items->cata_id == $category->cata_id? 'selected' : null }}>{{ $category->cata_name }}</option>
                                    @endforeach
                                    </select>
                                    @if($errors->has('cata_id'))
                                        <span class="text-danger">{{ $errors->first('cata_id')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Brand</label>
                                    <select name="brand_id" id="" class="form-control" required=""> 
                                        <option value="">---Select---</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->brand_id }}" {{ $items->brand_id == $brand->brand_id? 'selected' : null }}>{{ $brand->brand_name }}</option>
                                    @endforeach
                                    </select>
                                    @if($errors->has('brand_id'))
                                        <span class="text-danger">{{ $errors->first('brand_id')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea rows="3" name="description" id="ckEditor" class="form-control"  placeholder="Description" >{{ $items->description }}</textarea>
                                    @if($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Item Image</label>
                                    <input type="file" name="item_image" placeholder="" class="form-control" />
                                    @if($errors->has('item_image'))
                                        <span class="text-danger">{{ $errors->first('item_image')}}</span>
                                    @endif
                                </div> 
                                <input type="hidden" name="item_image_old" value="{{ $items->item_image }}" />
                                <div class="form-group">
                                    <label for="">Opening Stock Quantity</label>
                                    <input type="text" name="opening_stock_qty" value="{{ $items->opening_stock_qty }}" class="form-control" Title="Number only" pattern="[0-9]+" required/>
                                    @if($errors->has('opening_stock_qty'))
                                        <span class="text-danger">{{ $errors->first('opening_stock_qty')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <br>
                                    <input type="submit" name="" value="Update" class="btn btn-warning">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>

</div>
    @endforeach
@endsection
@endif