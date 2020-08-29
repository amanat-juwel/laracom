@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Create New Customer
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Create Customer</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-12">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form" action="{{ url('admin/customer') }}" method="post" enctype="multipart/form-data">
                              {!! csrf_field() !!}
                                <h4 class="text text-info" style="border-left: 4px solid #659be0;"> <b>&nbspPersonal Info</b></h4>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="">Customer Name <span class="required">*</span></label>
                                    <input type="text" autocomplete="OFF" name="customer_name" placeholder="Customer Name" class="form-control input-sm" required />
                                    @if($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name')}}</span>
                                    @endif
                                </div>
                              
                                <div class="form-group">
                                    <label for="">Mobile No</label>
                                    <input type="text" autocomplete="OFF" name="mobile_no" placeholder="Mobile No" class="form-control input-sm" />
                                    @if($errors->has('mobile_no'))
                                        <span class="text-danger">{{ $errors->first('mobile_no')}}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" autocomplete="OFF" name="email" placeholder="Email" class="form-control input-sm" />
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email')}}</span>
                                    @endif
                                </div>
                                
                                
                        
                        </div>
                        <div class="col-md-6">
                                <h4>&nbsp</h4>
                                  <!-- <div class="form-group">
                                    <label for=""> NID/Identification No</label>
                                    <input type="text" autocomplete="OFF" name="customer_nid" placeholder="Customer NID" class="form-control input-sm" maxlength="17" pattern="[0-9]+" title="17 digit NID Number"/>
                                    @if($errors->has('customer_nid'))
                                        <span class="text-danger">{{ $errors->first('customer_nid')}}</span>
                                    @endif
                                </div> -->
                                <div class="form-group">
                                    <label for=""> Image</label>
                                    <input type="file" name="customer_image" placeholder="" class="form-control input-sm" />
                                    @if($errors->has('customer_image'))
                                        <span class="text-danger">{{ $errors->first('customer_image')}}</span>
                                    @endif
                                </div> 
                               
                                <div class="form-group">
                                    <label for="">Category <span class="required">*</span></label><br>
                                    <select name="category" id="cust_category" class="form-control select2" required=""> 
                                        <option value=''>---Select---</option>
                                        @foreach($customer_categories as $data)
                                            <option value="{{ $data->id }}">{{$data->cat_name}} </option>
                                        @endforeach
                                    </select>
                                </div> 
                                
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title"><i class="fa fa-book"></i>  Billing Address</h4>
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
                                                  Delivery and billing addresses are the same.</label>
                                              </div>
                                            </div>
                                          </div>
                                        </fieldset>
                                      </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="panel panel-default delivery-address">
                                <div class="panel-heading">
                                  <h4 class="panel-title"><i class="fa fa-book"></i>  Delivery Address</h4>
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
                        </div>
                        <div class="col-md-4">
                                <h4 class="text text-info" style="border-left: 4px solid #659be0;"> <b>&nbspBalance Info</b></h4>
                                <div class="form-group">
                                    <label class="text-green">Debit Balance [Receivable from Customer]</label>
                                    <input type="text" name="debit" placeholder="" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('debit'))
                                        <span class="text-danger">{{ $errors->first('debit')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="text-red">Credit Balance [Advanced from Customer]</label>
                                    <input type="text" name="credit" placeholder="" autocomplete="OFF" class="form-control input-sm" />
                                    @if($errors->has('credit'))
                                        <span class="text-danger">{{ $errors->first('credit')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success pull-right" value="Save"/>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->

    </div>
</div>
</form>
<!--
<script src="{{ asset('public/js/webcam.min.js') }}"></script>
 -->
        <!-- Configure a few settings and attach camera -->
  <!--      <script language="JavaScript">
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach( '#my_camera' );
        </script>
        <script language="JavaScript">
            function myFunction() {
                var x = document.getElementById("myDIV");
                if (x.style.display === "none") {
                    x.style.display = "block";
                    document.getElementById('close').style.display = 'none';
                } 
            }
            function preview_snapshot() {
                // freeze camera so user can preview pic
                Webcam.freeze();
                
                // swap button sets
                document.getElementById('pre_take_buttons').style.display = 'none';
                document.getElementById('post_take_buttons').style.display = '';
            }
            
            function cancel_preview() {
                // cancel preview freeze and return to live camera feed
                Webcam.unfreeze();
                
                // swap buttons back
                document.getElementById('pre_take_buttons').style.display = '';
                document.getElementById('post_take_buttons').style.display = 'none';
            }
            
            function save_photo() {
                // actually snap photo (from preview freeze) and display it
                Webcam.snap( function(data_uri) {
                    // display results in page
                    document.getElementById('results').innerHTML = 
                    '<img src="'+data_uri+'" width="300" height="300"><input type="text" name="customer_image" value ="'+data_uri+'" style="display:none" />';
                        
                    
                    // swap buttons back
                    document.getElementById('pre_take_buttons').style.display = 'none';
                    document.getElementById('post_take_buttons').style.display = 'none';
                    document.getElementById('my_camera').style.display = 'none';
                } );
            }
        </script>
        -->

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