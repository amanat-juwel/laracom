@extends('layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Edit Customer Details
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Edit Customer Details</li>
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
                            <form class="form" action="{{ url('/customer/'.$customers->customer_id) }}" method="post">
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $id }}">
                                <div class="form-group">
                                    <label for="">Customer Name*</label>
                                    <input type="text" name="customer_name" placeholder="Customer Name" class="form-control" value="{{ $customers->customer_name }}" required/>
                                    @if($errors->has('customer_name'))
                                        <span class="text-danger">{{ $errors->first('customer_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Mobile No</label>
                                    <input type="text" name="mobile_no" placeholder="Mobile No" class="form-control" value="{{ $customers->mobile_no }}" />
                                    @if($errors->has('mobile_no'))
                                        <span class="text-danger">{{ $errors->first('mobile_no')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Customer NID</label>
                                    <input type="text" name="customer_nid" placeholder="Customer NID" class="form-control" value="{{ $customers->customer_nid }}" />
                                    @if($errors->has('customer_nid'))
                                        <span class="text-danger">{{ $errors->first('customer_nid')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" name="email" placeholder="Email" class="form-control" value="{{ $customers->email }}" />
                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <textarea rows="3" name="address" class="form-control"  placeholder="Address" >{{ $customers->address }}</textarea>
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Passport</label>
                                    <input type="text" name="passport" placeholder="Passport" class="form-control" value="{{ $customers->passport }}" />
                                    @if($errors->has('passport'))
                                        <span class="text-danger">{{ $errors->first('passport')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Customer TIN</label>
                                    <input type="text" name="customer_tin" placeholder="Customer TIN" class="form-control" value="{{ $customers->customer_tin }}" />
                                    @if($errors->has('customer_tin'))
                                        <span class="text-danger">{{ $errors->first('customer_tin')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Driving License</label>
                                    <input type="text" name="driving_license" placeholder="Driving License" class="form-control" value="{{ $customers->driving_license }}" />
                                    @if($errors->has('driving_license'))
                                        <span class="text-danger">{{ $errors->first('driving_license')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Customer Image</label>
                                    <img class="img-responsive img-rounded" id="old_image" src="{!! url('/public/images/customer_images/'.$customers->customer_image) !!}" alt="{{ $customers->customer_image }}" width="400">
                                    <input type="button" value="Take Photo" class="form-control" onclick="myFunction()"  id="close"/>
                                    <div id="myDIV" style="display:none">
                                        <div id="my_camera"></div>
                                        <div id="results">Your captured image will appear here...</div>
                                        <div id="pre_take_buttons">
                                            <input type="button" value="Take Snapshot" onClick="preview_snapshot()">
                                        </div>
                                        <div id="post_take_buttons" style="display:none">
                                            <input type="button" value="&lt; Take Another" onClick="cancel_preview()">
                                            <input type="button" value="Save Photo &gt;" onClick="save_photo()" style="font-weight:bold;">
                                        </div>
                                    </div>
                                    @if($errors->has('customer_image'))
                                        <span class="text-danger">{{ $errors->first('customer_image')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" value="Save"/>
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
<script src="{{ asset('public/js/webcam.min.js') }}"></script>

        <!-- Configure a few settings and attach camera -->
        <script language="JavaScript">
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
                    document.getElementById('old_image').style.display = 'none';
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

@endsection