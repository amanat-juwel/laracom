@extends('frontend.layouts.master')

@section('title','Checkout')

@section('body')

<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li><a href=""><i class="fa fa-home"></i></a></li>
        <li><a href="">Rigister</a></li>

      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-12">
          <h1 class="title">Checkout</h1>
          <div class="row">
            <div class="col-sm-4">
              @if(Auth::user()==null)
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><i class="fa fa-sign-in"></i> Create an Account or Login</h4>
                </div>
                  <div class="panel-body">
                        <div class="radio">
                          <label>
                            <input type="radio" value="register" checked="checked" name="account">
                            Register Account</label>
                        </div>
                  </div>
              </div>
         
             
              @endif

            </div>

            <form action="{{url('web/register')}}" method="post">
            @csrf
            <div class="col-sm-8">
              @if(Auth::user()==null)
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><i class="fa fa-user"></i> Your Personal Details</h4>
                </div>
                  <div class="panel-body">
                        <fieldset id="account">
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-firstname" class="control-label">Name</label>
                                <input type="text" class="form-control" id="input-payment-firstname" placeholder="Name" value="" name="name">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-email" class="control-label">E-Mail</label>
                                <input type="text" class="form-control" id="input-payment-email" placeholder="E-Mail" value="" name="email">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-telephone" class="control-label">Telephone</label>
                                <input type="text" class="form-control" id="input-payment-telephone" placeholder="Telephone" value="" name="mobile_no">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-telephone" class="control-label">Gender</label>
                                <br>
                                <input type="radio" name="gender" checked="" value="Male">
                                Male</label> <input type="radio" name="gender" value="Female">
                                Female</label>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-firstname" class="control-label">Password</label>
                                <input type="password" class="form-control" id="input-payment-firstname" placeholder="" value="" name="password">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-email" class="control-label">Confirm Password</label>
                                <input type="password" class="form-control" id="input-payment-email" placeholder="" value="" name="password_confirm">
                              </div>
                            </div>
                          </div>
                        </fieldset>
                      </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><i class="fa fa-book"></i> Your Billing Address</h4>
                </div>
                  <div class="panel-body">
                        <fieldset id="address" class="required">
                          <div class="form-group required">
                            <label for="input-payment-company" class="control-label">Name</label>
                            <input type="text" class="form-control" id="input-payment-company" placeholder="Name" value="" name="name_billing">
                          </div>
                          <div class="form-group required">
                            <label for="input-payment-company" class="control-label">Phone</label>
                            <input type="text" class="form-control" id="input-payment-company" placeholder="Phone" value="" name="mobile_no_billing">
                          </div>
                          <div class="form-group required">
                            <label for="input-payment-address-1" class="control-label">Address</label>
                            <input type="text" class="form-control" id="input-payment-address-1" placeholder="Address" value="" name="address_billing">
                          </div>
                            
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-city" class="control-label">City</label>
                                <input type="text" class="form-control" id="input-payment-city" placeholder="City" value="" name="city_billing">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-postcode" class="control-label">Post Code</label>
                                <input type="text" class="form-control" id="input-payment-postcode" placeholder="Post Code" value="" name="postal_code_billing">
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
                                  <input type="checkbox"  value="1" name="shipping_address" id="shipping_address">
                                  My delivery and billing addresses are the same.</label>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                      </div>
              </div>

              <div class="panel panel-default delivery-address">
                <div class="panel-heading">
                  <h4 class="panel-title"><i class="fa fa-book"></i> Your Delivery Address</h4>
                </div>
                  <div class="panel-body">
                        <fieldset id="address" class="required">
                          <div class="form-group required">
                            <label for="input-payment-company" class="control-label">Name</label>
                            <input type="text" class="form-control" id="input-payment-company" placeholder="Name" value="" name="name_delivery">
                          </div>
                          <div class="form-group required">
                            <label for="input-payment-company" class="control-label">Phone</label>
                            <input type="text" class="form-control" id="input-payment-company" placeholder="Phone" value="" name="mobile_no_delivery">
                          </div>
                          <div class="form-group required">
                            <label for="input-payment-address-1" class="control-label">Address</label>
                            <input type="text" class="form-control" id="input-payment-address-1" placeholder="Address" value="" name="address_delivery">
                          </div>
                            
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-city" class="control-label">City</label>
                                <input type="text" class="form-control" id="input-payment-city" placeholder="City" value="" name="city_delivery">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-postcode" class="control-label">Post Code</label>
                                <input type="text" class="form-control" id="input-payment-postcode" placeholder="Post Code" value="" name="postal_code_delivery">
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group required">
                                <label for="input-payment-country" class="control-label">Country</label>
                                <select class="form-control" id="input-payment-country" name="country_delivery">
                                  <option value="Bangladesh">Bangladesh</option>
                                </select>
                              </div>
                            </div>
                            
                          </div>
                        </fieldset>
                      </div>
                  </div>
                 @endif
              

                    <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="buttons">
                            <div class="pull-right">
                              <input type="submit" class="btn btn-primary" id="button-confirm" value="Register">
                            </div>
                          </div>
                        </div>
                    </div>
              </div>
            </div>
            </form>
            <div class="col-sm-8">
              <div class="row">
                <!-- <div class="col-sm-6">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title"><i class="fa fa-truck"></i> Delivery Method</h4>
                    </div>
                      <div class="panel-body">
                        <p>Please select the preferred shipping method to use on this order.</p>
                        <div class="radio">
                          <label>
                            <input type="radio" checked="checked" name="Free Shipping">
                            Free Shipping - $0.00</label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="Flat Shipping Rate">
                            Flat Shipping Rate - $8.00</label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="Per Item Shipping Rate">
                            Per Item Shipping Rate - $150.00</label>
                        </div>
                      </div>
                  </div>
                </div> -->
                
                <!-- <div class="col-sm-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title"><i class="fa fa-ticket"></i> Use Coupon Code</h4>
                    </div>
                      <div class="panel-body">
                        <label for="input-coupon" class="col-sm-3 control-label">Enter coupon code</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="input-coupon" placeholder="Enter your coupon here" value="" name="coupon">
                          <span class="input-group-btn">
                          <input type="button" class="btn btn-primary" data-loading-text="Loading..." id="button-coupon" value="Apply Coupon">
                          </span></div>
                      </div>
                  </div>
                </div> -->
                <!-- <div class="col-sm-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h4 class="panel-title"><i class="fa fa-gift"></i> Use Gift Voucher</h4>
                    </div>
                      <div class="panel-body">
                        <label for="input-voucher" class="col-sm-3 control-label">Enter gift voucher code</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="input-voucher" placeholder="Enter your gift voucher code here" value="" name="voucher">
                          <span class="input-group-btn">
                          <input type="submit" class="btn btn-primary" data-loading-text="Loading..." id="button-voucher" value="Apply Voucher">
                          </span> </div>
                      </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
        <!--Middle Part End -->
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