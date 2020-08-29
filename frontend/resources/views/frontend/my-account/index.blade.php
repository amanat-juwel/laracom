@extends('frontend.layouts.master')

@section('title','My Account')

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
        <li><a href="my-account.html">My Account</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div class="col-sm-9" id="content">
          <h1 class="title">My Account</h1>
          <p class="lead">Hello, <strong>{{Auth::user()->name}}!</strong> - To update your account information.</p>
          <form>
          <div class="row">
            <div class="col-sm-6">
            <fieldset id="personal-details">
              <legend>Personal Details</legend>
              <div class="form-group required">
                <label for="input-firstname" class="control-label">Full Name</label>
                  <input type="text" class="form-control" id="input-firstname" placeholder="Full Name" value="{{Auth::user()->name}}" name="name">
              </div>
              <div class="form-group required">
                <label for="input-email" class="control-label">E-Mail</label>
                  <input type="email" class="form-control" id="input-email" placeholder="E-Mail" value="{{Auth::user()->email}}" name="email">
              </div>
              <div class="form-group required">
                <label for="" class="control-label">Mobile</label>
                  <input type="text" class="form-control" id="" placeholder="Last Name" value="{{$customer->mobile_no}}" name="mobile_no">
              </div>
              <div class="form-group required">
                <label for="input-telephone" class="control-label">Gender</label>
                <select class="form-control" name="gender">
                  <option value="Male" @if($customer->gender=='Male') selected @endif>Male</option>
                  <option value="Female" @if($customer->gender=='Female') selected @endif>Female</option>
                </select>
              </div>
            </fieldset><br>
            </div>
            <div class="col-sm-6">
            <fieldset>
              <legend>Change Password</legend>
              <div class="form-group required">
                <label for="input-password" class="control-label">Old Password</label>
                  <input type="password" class="form-control" id="input-password" placeholder="Old Password" value="" name="old-password">
              </div>
              <div class="form-group required">
                <label for="input-password" class="control-label">New Password</label>
                  <input type="password" class="form-control" id="input-password" placeholder="New Password" value="" name="new-password">
              </div>
              <div class="form-group required">
                <label for="input-confirm" class="control-label">New Password Confirm</label>
                  <input type="password" class="form-control" id="input-confirm" placeholder="New Password Confirm" value="" name="new-confirm">
              </div>
            </fieldset>
            
            <!-- <fieldset>
              <legend>Newsletter</legend>
              <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-3 control-label">Subscribe</label>
                <div class="col-md-10 col-sm-9 col-xs-9">
                  <label class="radio-inline">
                    <input type="radio" value="1" name="newsletter">
                    Yes</label>
                  <label class="radio-inline">
                    <input type="radio" checked="checked" value="0" name="newsletter">
                    No</label>
                </div>
              </div>
            </fieldset> -->
            
            </div>
            </div>
            <div class="row">
            <div class="col-sm-6">
            <fieldset id="address">
              <legend>Billing Address</legend>
              <div class="form-group required">
                <label for="input-payment-company" class="control-label">Name</label>
                <input type="text" class="form-control" id="input-payment-company" placeholder="Name" value="{{$billing_address->fullname}}" name="name_billing">
              </div>
              <div class="form-group required">
                <label for="input-payment-company" class="control-label">Phone</label>
                <input type="text" class="form-control" id="input-payment-company" placeholder="Phone" value="{{$billing_address->mobile}}" name="mobile_no_billing">
              </div>
              <div class="form-group required">
                <label for="input-payment-address-1" class="control-label">Address</label>
                <input type="text" class="form-control" id="input-payment-address-1" placeholder="Address" value="{{$billing_address->address}}" name="address_billing">
              </div>
                
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label for="input-payment-city" class="control-label">City</label>
                    <input type="text" class="form-control" id="input-payment-city" placeholder="City" value="{{$billing_address->city}}" name="city">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label for="input-payment-postcode" class="control-label">Post Code</label>
                    <input type="text" class="form-control" id="input-payment-postcode" placeholder="Post Code" value="{{$billing_address->postal_code}}" name="postal_code_billing">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label for="input-payment-country" class="control-label">Country</label>
                    <select class="form-control" id="input-payment-country" name="country_billing">
                      <option value="Bangladesh">Bangladesh</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="checkbox">
                    <label>
                      <br>
                      <input type="checkbox"  value="1" name="shipping_address" id="shipping_address" @if($customer->billing_address_id==$customer->delivery_address_id) checked @endif>
                      My delivery and billing addresses are the same.</label>
                  </div>
                </div>
            </fieldset>
            </div>
            <div class="col-sm-6">
            <fieldset id="shipping-address" class="delivery-address @if($customer->billing_address_id==$customer->delivery_address_id) hidden @endif">
              <legend>Shipping Address</legend>
              <div class="form-group required">
                <label for="input-payment-company" class="control-label">Name</label>
                <input type="text" class="form-control" id="input-payment-company" placeholder="Name" value="{{$delivery_address->fullname}}" name="name_billing">
              </div>
              <div class="form-group required">
                <label for="input-payment-company" class="control-label">Phone</label>
                <input type="text" class="form-control" id="input-payment-company" placeholder="Phone" value="{{$delivery_address->mobile}}" name="mobile_no_billing">
              </div>
              <div class="form-group required">
                <label for="input-payment-address-1" class="control-label">Address</label>
                <input type="text" class="form-control" id="input-payment-address-1" placeholder="Address" value="{{$delivery_address->address}}" name="address_billing">
              </div>
                
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label for="input-payment-city" class="control-label">City</label>
                    <input type="text" class="form-control" id="input-payment-city" placeholder="City" value="{{$delivery_address->city}}" name="city">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label for="input-payment-postcode" class="control-label">Post Code</label>
                    <input type="text" class="form-control" id="input-payment-postcode" placeholder="Post Code" value="{{$delivery_address->postal_code}}" name="postal_code_billing">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label for="input-payment-country" class="control-label">Country</label>
                    <select class="form-control" id="input-payment-country" name="country_billing">
                      <option value="Bangladesh">Bangladesh</option>
                    </select>
                  </div>
                </div>
            </fieldset>
            </div>
            </div>
            
            
            
            <div class="buttons">
              <div class="pull-right">
                <!-- <input type="submit" class="btn btn-lg btn-primary" value="Save Changes"> -->
              </div>
            </div>
          </form>
        </div>
        <!--Middle Part End -->
        <!--Right Part Start -->
        @include('frontend.components.my-account-sidebar')
        <!--Right Part End -->
      </div>
    </div>
  </div>
@endsection


@section('script')

<script>


  $("#shipping_address").change(function() {
      shippingAddressChangeDetector(this.checked);
  });

  function shippingAddressChangeDetector(flag){
        if(flag){
            $('.delivery-address').addClass('hidden');
        }
        else{
            $('.delivery-address').removeClass('hidden');
        }
    }
  
</script>
@endsection