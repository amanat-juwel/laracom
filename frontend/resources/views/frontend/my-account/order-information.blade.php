@extends('frontend.layouts.master')

@section('title','Order Information')

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
        <li><a href="">Order Information</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
        <h1 class="title">Order Information</h1>
        
        <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td colspan="2" class="text-left">Order Details</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width: 50%;" class="text-left">
              <b>Order ID:</b> #{{$orderObj->id}}<br>
              <b>Order Date:</b> {{$orderObj->datetime}}</td>
            <td style="width: 50%;" class="text-left">
              <b>Payment Method:</b> {{$orderObj->payment_method}}<br>
              <!-- <b>Shipping Method:</b> Flat Shipping Rate     -->          
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td style="width: 50%; vertical-align: top;" class="text-left">Address</td>
            <!--<td style="width: 50%; vertical-align: top;" class="text-left">Shipping Address</td>-->
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left">
              {{$billing_address->fullname}}<br>
              {{$billing_address->mobile}}<br>
              {{$billing_address->address}}<br>
              {{$billing_address->city}}, {{$billing_address->postal_code}}<br>{{$billing_address->country}}
            </td>
           <!--<td class="text-left">-->
           <!--   {{$delivery_address->fullname}}<br>-->
           <!--   {{$delivery_address->mobile}}<br>-->
           <!--   {{$delivery_address->address}}<br>-->
           <!--   {{$delivery_address->city}}, {{$delivery_address->postal_code}}<br>{{$delivery_address->country}}-->
           <!-- </td>-->
          </tr>
        </tbody>
      </table>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left">Image</td>
              <td class="text-left">Product Name</td>
              <td class="text-right">Quantity</td>
              <td class="text-right">Price</td>
              <td class="text-right">Total</td>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach($order_details as $data)
            <tr>
              <td class="text-center"><a href=""><img class="img-thumbnail" src="{{asset($data->item_image)}}" width="80px" height"80px"></a></td>
              <td class="text-left">{{$data->item_name}}</td>
              <td class="text-right">{{$data->qty}}</td>
              <td class="text-right">BDT {{$data->rate}}</td>
              <td class="text-right">BDT {{$data->qty*$data->rate}}</td>
            </tr>
            @php $total += ($data->qty*$data->rate); @endphp
            @endforeach
                        
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="text-right"><b>Total</b></td>
              <td class="text-right">BDT {{$total}}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    <h3>Order History</h3>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left">Date Added</td>
            <td class="text-left">Status</td>
          </tr>
        </thead>
        <tbody>
          @foreach($order_history as $data)
           <tr>
            <td class="text-left">{{$data->created_at}}</td>
            <td class="text-left">{{$data->status}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
         
              
            
               
        </div>
        <!--Middle Part End -->
        <!--Right Part Start -->
        @include('frontend.components.my-account-sidebar')
        <!--Right Part End -->
      </div>
    </div>
  </div>
@endsection