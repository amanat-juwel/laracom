@extends('admin.layouts.template')

@section('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
@endsection

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        WEB HOMEPAGE SETTINGS
        <small></small>
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Web Homepage Settings</li>
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
              <form class="form-horizontal" name="settings_form" action="{{ url('admin/web-homepage') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
              
                <div class="col-md-6">
                  <div class="panel panel-primary">
                    <div class="panel-heading">Settings</div>
                    <div class="panel-body">
        
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Banner - 1</label>
                          <div class="col-sm-8">
                            <input type="file" name="banner_1" class="form-control input-sm" />
                          </div>
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Banner - 2</label>
                          <div class="col-sm-8">
                            <input type="file" name="banner_2" class="form-control input-sm" />
                          </div>
                        </div> 
                        <div class="form-group">
                          <label class="control-label col-sm-4" for="email">Banner - 3</label>
                          <div class="col-sm-8">
                            <input type="file" name="banner_3" class="form-control input-sm" />
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
                <div class="col-md-6">
                <div class="table-responsive">
                        <div class="box box-solid box-success">
                            <div class="box-header">
                                <h3 class="box-title"><i class="fa fa-cogs"></i> Current Setting</h3>
                            </div>
                            <div class="box-body">
                                <div class="profile-image col-md-12">
                                    <p><b>Banner - 1 :</b></p>
                                    <img src='{{ asset("$settings->banner_1") }}' id="myImage" class="img-responsive" alt="Logo" >
                                    <p><b>Banner - 2 :</b></p>
                                    <img src='{{ asset("$settings->banner_2") }}' id="myImage" class="img-responsive" alt="Logo" >
                                    <p><b>Banner - 3 :</b></p>
                                    <img src='{{ asset("$settings->banner_3") }}' id="myImage" class="img-responsive" alt="Logo" >

                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
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
