@extends('frontend.layouts.master')

@section('title','List of Brands')

@section('body')

<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i></a></li>
        <li><a href="{{url('web/brands')}}">Brand</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-12">
        <h1 class="title">Find Your Favorite Brand</h1>
            
            <div class="row">
              @foreach($brands_front_view as $brand)
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="{{url('web/brands/'.$brand->brand_name)}}" class="thumbnail"><img alt="{{$brand->brand_name}}" title="{{$brand->brand_name}}" src="{{asset($brand->brand_image)}}"></a>
                   
                </div>    
              @endforeach            
            </div>

            <!--
            <p class="brand-index"><strong>Brand Index:</strong>
                <a href="#A">A</a>
                <a href="#C">C</a>
                <a href="#H">H</a>
                <a href="#P">P</a>
                <a href="#S">S</a>
              </p>
              
            <div class="manufacturer-info">
            <h4 id="A" class="manufacturer-title">A</h4>
            <div class="row">
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="Apple" title="Apple" src="image/product/apple_logo-60x60.jpg"></a>
                    <a href="#">Apple</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">America Online</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Ambien</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Amgen</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Amstel Light</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Angel Soft</a>
                </div>
                
              </div>
              
              
              <h2 id="C" class="manufacturer-title">C</h2>
            <div class="row">
            <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="canon" title="Canon" src="image/product/canon_logo-60x60.jpg"></a>
                    <a href="#">Canon</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Camel</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Cartier</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Casio</a>
                </div>
                    </div>
              
              <h2 id="H" class="manufacturer-title">H</h2>
            <div class="row">
            <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="hp" title="HP" src="image/product/hp_logo-60x60.jpg"></a>
                    <a href="#">Hewlett-Packard</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="htc" title="htc" src="image/product/htc_logo-60x60.jpg"></a>
                    <a href="#">HTC</a>
                </div>
              </div>
              
              <h2 id="P" class="manufacturer-title">P</h2>
            <div class="row">
            <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="palm" title="Palm" src="image/product/palm_logo-60x60.jpg"></a>
                    <a href="#">Palm</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Perrier</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Philips</a>
                </div>
                <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="test" title="test" src="image/no_image_60x60.jpg"></a>
                    <a href="#">Puma</a>
                </div>
              </div>
              
              <h2 id="S" class="manufacturer-title">S</h2>
            <div class="row">
            <div class="col-sm-2 col-xs-6 manufacturer">
                  <a href="#" class="thumbnail"><img alt="sony" title="Sony" src="image/product/sony_logo-60x60.jpg"></a>
                    <a href="#">Sony</a>
                </div>
              </div>
            </div>
            -->
               
        </div>
        <!--Middle Part End -->
      </div>
    </div>
  </div>
@endsection