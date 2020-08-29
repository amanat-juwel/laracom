@extends('frontend.layouts.master')

@section('title','My Wish List')

@section('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<style>
    .swal2-select{
        display: none !important;
    }
</style>
@endsection

@section('body')

<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li><a href="index.html"><i class="fa fa-home"></i></a></li>
        <li><a href="#">Account</a></li>
        <li><a href="wishlist.html">My Wish List</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-12">
          <h1 class="title">My Wish List</h1>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-center">Image</td>
                  <td class="text-left">Product Name</td>
                  <td class="text-right">Unit Price</td>
                  <td class="text-right">Action</td>
                </tr>
              </thead>
              <tbody>
                @foreach($items as $item)
                <tr>
                  <td class="text-center"><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}"><img src="http://demo.harnishdesign.net/html/marketshop/v3/image/product/samsung_tab_1-50x50.jpg" alt="Aspire Ultrabook Laptop" title="Aspire Ultrabook Laptop" /></a></td>
                  <td class="text-left"><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}">{{$item->item_name}}</a></td>
                  <td class="text-right"><div class="price">@if(empty($item->discounted_price))BDT{{number_format($item->mrp,2)}}@else{{$item->discounted_price}}@endif</div></td>
                  <td class="text-right">
                    <form action="{{ url('web/add-to-cart') }}" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" name="item_id" value="{{$item->item_id}}"/>
                      <input type="hidden" name="item_name" value="{{$item->item_name}}"/>
                      <input type="hidden" name="price" value="@if(empty($item->discounted_price)){{$item->mrp}}@else{{$item->discounted_price}}@endif"/>
                      <input type="hidden" name="quantity" value="1" size="2" id="input-quantity" class="form-control" />
                      <button type="submit" id="button-cart" title="Add to Cart" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></button>
                    </form>
                    
                    <form action="{{ url('/web/remove-from-wishlist/'.$item->item_id) }}" method="post">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" data-toggle="tooltip" title="Remove" class="btn btn-danger">
                        <i class="fa fa-times" aria-hidden="true"> </i></button>
                    </form>
                 </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!--Middle Part End -->
      </div>
    </div>
  </div>
@endsection