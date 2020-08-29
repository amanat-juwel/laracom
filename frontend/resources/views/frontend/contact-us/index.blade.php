@extends('frontend.layouts.master')

@section('title','Contact')

@section('body')

<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li><a href="index.html"><i class="fa fa-home"></i></a></li>
        <li><a href="{{url('web/contact-us')}}">Contact Us</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
          <h1 class="title">Contact Us</h1>
          <h3 class="subtitle">Our Location</h3>
          <div class="row">
            <div class="col-sm-4">
              <div class="contact-info">
                <div class="contact-info-icon"><i class="fa fa-map-marker"></i></div>
                <div class="contact-detail">
                  <h4>Location</h4>
                  <address>
                  {!!$globalSettings->address!!}
                  </address>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="contact-info">
                <div class="contact-info-icon"><i class="fa fa-phone"></i></div>
                <div class="contact-detail">
                  <h4>Telephone</h4>
                  @if($globalSettings->phone!='')Phone: {{ $globalSettings->phone }} <br>@endif
                  @if($globalSettings->mobile!='')Mobile: {{ $globalSettings->mobile }} <br>@endif
                  @if($globalSettings->fax!='')Fax: {{ $globalSettings->fax }} <br>@endif
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="contact-info">
                <div class="contact-info-icon"><i class="fa fa-envelope"></i></div>
                <div class="contact-detail">
                  <h4>Email</h4>
                  {{ $globalSettings->email }}</div>
              </div>
            </div>
          </div>
          <!-- <form class="form-horizontal">
            <fieldset>
              <h3 class="subtitle">Send us an Email</h3>
              <div class="form-group required">
                <label class="col-md-2 col-sm-3 control-label" for="input-name">Your Name</label>
                <div class="col-md-10 col-sm-9">
                  <input type="text" name="name" value="" id="input-name" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-md-2 col-sm-3 control-label" for="input-email">E-Mail Address</label>
                <div class="col-md-10 col-sm-9">
                  <input type="text" name="email" value="" id="input-email" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-md-2 col-sm-3 control-label" for="input-enquiry">Enquiry</label>
                <div class="col-md-10 col-sm-9">
                  <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"></textarea>
                </div>
              </div>
            </fieldset>
            <div class="buttons">
              <div class="pull-right">
                <input class="btn btn-primary" type="submit" value="Submit" />
              </div>
            </div>
          </form> -->
        </div>
        <!-- <aside id="column-right" class="col-sm-3 hidden-xs">
          <div class="list-group">
            <h2 class="subtitle">Custom Content</h2>
            <p>This is a CMS block edited from admin. You can insert any content (HTML, Text, Images) Here. </p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
            <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
          </div>
          <div class="banner owl-carousel">
            <div class="item"> <a href="#"><img src="image/banner/small-banner1-265x350.jpg" alt="small banner" class="img-responsive" /></a> </div>
            <div class="item"> <a href="#"><img src="image/banner/small-banner-265x350.jpg" alt="small banner1" class="img-responsive" /></a> </div>
          </div>
        </aside> -->
        <!--Middle Part End -->
      </div>
    </div>
  </div>
@endsection