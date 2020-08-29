@extends('admin.layouts.template')

@section('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        SETTINGS
        <small></small>
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Settings</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
        
            <div class="row">
                @if(Session::has('success'))
                      <script type="text/javascript">
                          Swal.fire({
                            //position: 'top-end',
                            type: 'success',
                            title: 'Success',
                            showConfirmButton: false,
                            timer: 2000
                          })
                      </script>
                      @php
                      Session::forget('success');
                      @endphp
                  @endif
              <form class="form-horizontal" name="settings_form" action="{{ url('admin/settings/update') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-md-6">
                  <div class="panel panel-primary">
                    <div class="panel-heading">Basic Info</div>
                    <div class="panel-body">
                      
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Company Name</label>
                          <div class="col-sm-8">
                            <input type="text" name="company_name" placeholder="Company Name" value="{{ $settings->company_name }}" class="form-control input-sm" required/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Website</label>
                          <div class="col-sm-8">
                            <input type="text" name="website" placeholder="" value="{{ $settings->website }}" class="form-control input-sm" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Address</label>
                          <div class="col-sm-8">
                            <textarea name="address" placeholder="Address" class="form-control input-sm">{{ $settings->address }}</textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Email</label>
                          <div class="col-sm-8">
                            <input type="text" name="email" placeholder="Email" value="{{ $settings->email }}" class="form-control input-sm"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Phone</label>
                          <div class="col-sm-8">
                            <input type="text" name="phone" placeholder="Phone" value="{{ $settings->phone }}" class="form-control input-sm"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Mobile</label>
                          <div class="col-sm-8">
                            <input type="text" name="mobile" placeholder="Mobile" value="{{ $settings->mobile }}" class="form-control input-sm"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Fax</label>
                          <div class="col-sm-8">
                            <input type="text" name="fax" placeholder="Fax" value="{{ $settings->fax }}" class="form-control input-sm"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Vat Registration No</label>
                          <div class="col-sm-8">
                            <input type="text" name="vat_registration_no" placeholder="Vat Registration No" value="{{ $settings->vat_registration_no }}" class="form-control input-sm" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Vat %</label>
                          <div class="col-sm-8">
                            <input type="number" name="vat_percent" placeholder="Vat %" value="{{ $settings->vat_percent }}" class="form-control input-sm " />
                          </div>
                        </div>
                         <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Default Discount Mode</label>
                          <div class="col-sm-8">
                            <select name="discount_mode"  id="discount_mode" class="form-control input-sm" />
                                <option value="Percentage">Percentage</option>
                                <option value="TK">TK</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Currency</label>
                          <div class="col-sm-8">
                            <input type="text" name="currency" placeholder="Currency" value="{{ $settings->currency }}" class="form-control input-sm" />
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Invoice Prefix</label>
                          <div class="col-sm-8">
                            <input type="text" name="invoice_prefix" placeholder="" value="{{ $settings->invoice_prefix }}" class="form-control input-sm"  maxlength="3" required="" />
                          </div>
                        </div>
                        
                    </div>
                  </div>
                </div>

                
                <div class="col-md-6">
                  <div class="panel panel-primary">
                    <div class="panel-heading">SMS Settings</div>
                    <div class="panel-body">
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Sender</label>
                          <div class="col-sm-8">
                            <input autocomplete="OFF" type="text" name="sms_sender" placeholder="Sender" value="{{ $settings->sms_sender }}" class="form-control input-sm"/>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">API KEY</label>
                          <div class="col-sm-8">
                            <textarea name="sms_api_key" placeholder="API KEY" class="form-control input-sm">{{ $settings->sms_api_key }}</textarea>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="panel panel-primary">
                    <div class="panel-heading">Appearance Settings</div>
                    <div class="panel-body">
                      
                        
                        <!-- <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Payment Instruction</label>
                          <div class="col-sm-8">
                            <textarea name="payment_instruction" id="ckEditor" class="form-control input-sm">{{ $settings->payment_instruction }}</textarea>
                          </div>
                        </div> -->
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Change Invoice Logo</label>
                          <div class="col-sm-8">
                            <input type="hidden" name="logo_old" value="{{ $settings->logo }}" />  
                            <input type="file" name="logo_new" class="form-control input-sm" />
                          </div>
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Change System Logo</label>
                          <div class="col-sm-8">
                            <input type="hidden" name="system_logo_old" value="{{ $settings->system_logo }}" />  
                            <input type="file" name="system_logo_new" class="form-control input-sm" />
                          </div>
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Change Favicon</label>
                          <div class="col-sm-8">
                            <input type="hidden" name="favicon_old" value="{{ $settings->favicon }}" />  
                            <input type="file" name="favicon_new" class="form-control input-sm" />
                          </div>
                        </div>  
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Full Sidebar</label>
                          <div class="col-sm-8">
                            <select class="form-control input-sm" name="full_sidebar">
                              <option value="0">No</option>
                              <option value="1">Yes</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Theme</label>
                          <div class="col-sm-8">
                            <select class="form-control input-sm" name="theme">
                              <option value="blue">Blue</option>
                              <option value="black">Black</option>
                              <option value="purple">Purple</option>
                              <option value="green">Green</option>
                              <option value="red">Red</option>
                              <option value="yellow">Yellow</option>
                              <option value="blue-light">Blue-light</option>
                              <option value="black-light">Black-light</option>
                              <option value="purple-light">Purple-light</option>
                              <option value="green-light">Green-light</option>
                              <option value="red-light">Red-light</option>
                              <option value="yellow-light">Yellow-light</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Profit Margin %</label>
                          <div class="col-sm-8">
                            <input type="number" name="profit_margin" placeholder="Ex: 20%" value="{{ $settings->profit_margin }}" class="form-control input-sm"/>
                          </div>
                        </div>
                        <div class="form-group"> 
                          <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-warning">Update</button>
                          </div>
                        </div>
                      
                    </div>
                  </div>

                </div>
                </form>
                <!-- <div class="col-md-6">
                <div class="table-responsive">
                        <div class="box box-solid box-success">
                            <div class="box-header">
                                <h3 class="box-title"><i class="fa fa-cogs"></i> Current Settings</h3>
                            </div>
                            <div class="box-body">
                                <div class="profile-details col-md-8">
                                    <p><b>COMPANY NAME :</b> {{ $settings->company_name }}</p>
                                    <p><b>EMAIL :</b> {{ $settings->email }}</p>
                                    <p><b>ADDRESS :</b> {{ $settings->address }}</p>
                                    <p><b>PHONE NO :</b> {{ $settings->phone }}</p>
                                    <p><b>MOBILE :</b> {{ $settings->mobile }}</p>
                                    <p><b>FAX :</b> {{ $settings->fax }}</p>
                                    <p><b>VAT REGISTRATION NO :</b> {{ $settings->vat_registration_no }}</p>
                                    <p><b>CURRENCY :</b> {{ $settings->currency }}</p>
                                    <p><b>Profit Margin :</b> {{ $settings->profit_margin }} %</p>
                                </div>
                                <div class="profile-image col-md-4">
                                    <p><b>LOGO :</b></p>
                                    <img src='{{ asset("$settings->logo") }}' id="myImage" class="img-responsive" alt="Logo" >
                                    <p><b>Favicon :</b></p>
                                    <img src='{{ asset("$settings->favicon") }}' class="img-responsive" alt="Favicon" >

                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
<script type="text/javascript">
  document.forms['settings_form'].elements['full_sidebar'].value="{{$globalSettings->full_sidebar}}";
  document.forms['settings_form'].elements['theme'].value="{{$globalSettings->theme}}";
  document.forms['settings_form'].elements['discount_mode'].value="{{$globalSettings->discount_mode}}";  
</script>


@endsection
