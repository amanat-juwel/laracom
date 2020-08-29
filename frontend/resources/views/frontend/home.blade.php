@extends('frontend.layouts.master')

@section('title')
HOME
@endsection

@section('body')

<div id="container">
    <div class="container">
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-xs-12">
          <div class="row">
            <div class="col-sm-8">
              <!-- Slideshow Start-->
              @include('frontend.components.slideshow')
              <!-- Slideshow End-->
            </div>
            
            <div class="col-sm-4 pull-right flip">
              <div class="marketshop-banner">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <a href="#"><img title="sample-banner1" alt="sample-banner1" src="{{asset('public/frontend/images/banner')}}/ban-1.jpg"></a></div>
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <a href="#"><img title="sample-banner" alt="sample-banner" src="{{asset('public/frontend/images/banner')}}/ban-2.jpg"></a></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Brand Logo Carousel Start-->
          <div id="carousel" class="owl-carousel nxt">
            @foreach($brands_front_view as $brand)
            <div class="item text-center"> <a href="{{url('web/brands/'.$brand->brand_name)}}"><img src="{{asset($brand->brand_image)}}" alt="{{$brand->brand_name}}" class="img-responsive" /></a> </div>
            @endforeach    
            <!-- <div class="item text-center"> <a href="#"><img src="http://demo.harnishdesign.net/html/marketshop/v3/image/product/canon_logo-100x100.jpg" alt="brand1" class="img-responsive" /></a> </div> -->
          </div>
          <!-- Brand Logo Carousel End -->

          <!-- Newly arrived Product Start-->
          @include('frontend.components.product.newly-arrived-products')
          <!-- Newly arrived Product End-->
          <!-- Banner Start-->
        <!--   <div class="marketshop-banner">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><a href="#"><img title="Sample Banner 2" alt="Sample Banner 2" src="{{asset('public/frontend/images/banner')}}/sample-banner-3-360x360.jpg"></a></div>
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><a href="#"><img title="Sample Banner" alt="Sample Banner" src="{{asset('public/frontend/images/banner')}}/sample-banner-1-360x360.jpg"></a></div>
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><a href="#"><img title="Sample Banner 3" alt="Sample Banner 3" src="{{asset('public/frontend/images/banner')}}/sample-banner-2-360x360.jpg"></a></div>
            </div>
          </div> -->
          <!-- Banner End-->
          <!-- Categories Product Slider Block 1 Start-->
          <!-- include('frontend.components.product.category-block-1') -->
          <!-- Categories Product Slider Block 1 End-->

          <!-- Categories Product Slider Block 2 Start-->
          <!-- include('frontend.components.product.category-block-2') -->
          <!-- Categories Product Slider Block 2 End -->
          <!-- Slim-Fixed-Banner Start -->
          <div class="marketshop-banner">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <a href="#"><img title="1 Block Banner" alt="1 Block Banner" src="{{asset('public/frontend/images/banner')}}/1blockbanner-1140x75.jpg"></a></div>
            </div>
          </div>
          <!-- Slim-Fixed-Banner End -->
          
        </div>
        <!--Middle Part End-->
      </div>
    </div>
  </div>
  <!-- Feature Box Start-->
  <!-- <div class="container">
    <div class="custom-feature-box row">
      <div class="col-sm-4 col-xs-12">
        <div class="feature-box fbox_1">
          <div class="title">Free Shipping</div>
          <p>Free shipping on order over $1000</p>
        </div>
      </div>
      <div class="col-sm-4 col-xs-12">
        <div class="feature-box fbox_3">
          <div class="title">Gift Cards</div>
          <p>Give the special perfect gift</p>
        </div>
      </div>
      <div class="col-sm-4 col-xs-12">
        <div class="feature-box fbox_4">
          <div class="title">Reward Points</div>
          <p>Earn and spend with ease</p>
        </div>
      </div>
    </div>
  </div> -->
  <!-- Feature Box End-->
@endsection