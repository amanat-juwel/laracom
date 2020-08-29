@extends('frontend.layouts.master')

@section('title','Order History')

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
        <li><a href="login.html">Account</a></li>
        <li><a href="order-history.html">Order History</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
        <h1 class="title">Order History</h1>
            <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
            <td class="text-center">Image</td>
              <td class="text-left">Product Name</td>
              <td class="text-center">Order ID</td>
              <td class="text-center">Qty</td>
              <td class="text-center">Status</td>
              <td class="text-center">Date Added</td>
              <td class="text-right">Total</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $data)
            <tr>
              <td class="text-center"><a href="product.html"><img class="img-thumbnail" title="Aspire Ultrabook Laptop" alt="Aspire Ultrabook Laptop" src="image/product/samsung_tab_1-50x50.jpg"></a></td>
              <td class="text-left"><a href="product.html">{{$data->item_name}}</a></td>
              <td class="text-center">#{{$data->order_id}}</td>
              <td class="text-center">{{$data->qty}}</td>
              <td class="text-center">{{$data->status}}</td>
              <td class="text-center">{{$data->datetime}}</td>
              <td class="text-right">BDT{{$data->qty*$data->rate}}</td>
              <td class="text-center"><a class="btn btn-info" title="" data-toggle="tooltip" href="{{url('web/order-information/'.$data->order_id)}}" data-original-title="View"><i class="fa fa-eye"></i></a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
              
            
              
               
        </div>
        <!--Middle Part End -->
        <!--Right Part Start -->
        @include('frontend.components.my-account-sidebar')
        <!--Right Part End -->
      </div>
    </div>
  </div>
@endsection