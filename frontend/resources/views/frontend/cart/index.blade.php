@extends('frontend.layouts.master')

@section('title','Cart')

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
        <li><a href="cart.html">Shopping Cart</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-12">
          <h1 class="title">Shopping Cart</h1>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <!-- <td class="text-center">Image</td> -->
                    <td class="text-left" style="width: 50%">Product Name</td>
                    <td class="text-left">Quantity</td>
                    <td class="text-right">Unit Price</td>
                    <td class="text-right">Total</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach(Cart::content() as $cart)
                  <tr>
                    <!-- <td class="text-center"><a href="{{url('web/products/'.$cart->id.'/'.$cart->name)}}"><img class="img-thumbnail" title="{{$cart->name}}" alt="{{$cart->name}}" src="http://demo.harnishdesign.net/html/marketshop/v3/image/product/sony_vaio_1-50x50.jpg"></a></td> -->
                    <td class="text-left"><a href="{{url('web/products/'.$cart->id.'/'.$cart->name)}}">{{$cart->name}}</a><br />
                      <!-- <small>Reward Points: 1000</small> --></td>
                    <td class="text-left">
                      <div class="row">
                        <form action="{{ url('/web/cart/'.$cart->rowId) }}" method="post">
                        <div class="col-md-8">
                          
                              {{ method_field('PUT') }}
                              {{ csrf_field() }}   
                              <input type="number" name="quantity" value="{{$cart->qty}}" size="1" class="form-control" />
                        </div>
                        <div class="col-md-2">
                          <span class="input-group-btn">
                                  <button type="submit" data-toggle="tooltip" title="Update" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                              </span>
                        </div>
                        </form>
                        <div class="col-md-2">
                          <div class="input-group btn-block quantity">
                            <form action="{{ url('/web/cart/'.$cart->rowId) }}" method="post">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" data-toggle="tooltip" title="Remove" class="btn btn-danger">
                                <i class="fa fa-times-circle" aria-hidden="true"> </i></button>
                            </form>
                          </div>
                        </div>
                      </div>
                      
                    </td>
                    <td class="text-right">{{$cart->price}}৳</td>
                    <td class="text-right">{{$cart->qty*$cart->price}}৳</td>
                  </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
          <!-- <h2 class="subtitle">What would you like to do next?</h2>
          <p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">Use Coupon Code</h4>
                </div>
                <div id="collapse-coupon" class="panel-collapse collapse in">
                  <div class="panel-body">
                    <label class="col-sm-4 control-label" for="input-coupon">Enter your coupon here</label>
                    <div class="input-group">
                      <input type="text" name="coupon" value="" placeholder="Enter your coupon here" id="input-coupon" class="form-control" />
                      <span class="input-group-btn">
                      <input type="button" value="Apply Coupon" id="button-coupon" data-loading-text="Loading..."  class="btn btn-primary" />
                      </span></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">Use Gift Voucher</h4>
                </div>
                <div id="collapse-voucher" class="panel-collapse collapse in">
                  <div class="panel-body">
                    <label class="col-sm-4 control-label" for="input-voucher">Enter gift voucher code here</label>
                    <div class="input-group">
                      <input type="text" name="voucher" value="" placeholder="Enter gift voucher code here" id="input-voucher" class="form-control" />
                      <span class="input-group-btn">
                      <input type="submit" value="Apply Voucher" id="button-voucher" data-loading-text="Loading..."  class="btn btn-primary" />
                      </span> </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          
          <div class="row">
            <div class="col-sm-4 col-sm-offset-8">
              <table class="table table-bordered">
                <tr>
                  <td class="text-right"><strong>Sub-Total:</strong></td>
                  <td class="text-right">BDT{{Cart::subtotal()}}</td>
                </tr>
                <!-- <tr>
                  <td class="text-right"><strong>Eco Tax (-2.00):</strong></td>
                  <td class="text-right">$4.00</td>
                </tr> -->
                <!-- <tr>
                  <td class="text-right"><strong>VAT (20%):</strong></td>
                  <td class="text-right">$188.00</td>
                </tr> -->
                <tr>
                  <td class="text-right"><strong>Total:</strong></td>
                  <td class="text-right">BDT{{Cart::subtotal()}}</td>
                </tr>
              </table>
            </div>
          </div>
          <div class="buttons">
            <div class="pull-left"><a href="{{url('/')}}" class="btn btn-default">Continue Shopping</a></div>
            <div class="pull-right"><a href="{{url('/web/checkout')}}" class="btn btn-primary">Checkout</a></div>
          </div>
        </div>
        <!--Middle Part End -->
      </div>
    </div>
  </div>
@endsection



@section('script')

@if(Session::has('success'))
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000
    });

    Toast.fire({
      type: 'success',
      title: "{{Session::get('success')}}"
    })
  </script>
  @php Session::forget('success');@endphp
@endif

@endsection