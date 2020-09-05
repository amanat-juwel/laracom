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
        <li><a href=""><i class="fa fa-home"></i></a></li>
        <li><a href="">Account</a></li>
        <li><a href="">Order History</a></li>
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
              <td class="text-center"><a href=""><img class="img-thumbnail" src="{{asset($data->item_image)}}" width="80px" height"80px"></a></td>
              <td class="text-left"><a href="">{{$data->item_name}}</a></td>
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