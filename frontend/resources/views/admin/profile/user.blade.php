@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Profile
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Profile</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if (Auth::guest())
                            @else
                            <div class="box box-solid box-success">
                                <div class="box-header">
                                    <h3 class="box-title">Admin Profile</h3>
                                </div><!-- /.box-header -->
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
                                    </div>
                                    <div class="profile-image col-md-4">
                                        <img src='{{ asset("$settings->logo") }}' class="img-responsive" alt="User Image" width="150" height="150">
                                        <!-- <form action="store" method="post" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <label for="shop_logo">Image Upload
                                            <input type="file" name="shop_logo"></label>
                                            <input type="submit" value="Submit" name="submit">
                                        </form> -->
                                    </div>
                                </div><!-- /.box-body -->
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- End Main Content -->
@endsection