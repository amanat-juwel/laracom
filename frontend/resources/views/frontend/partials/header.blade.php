<header class="header-row">
  <div class="container">
    <div class="table-container">
      <!-- Logo Start -->
      <div class="col-table-cell col-lg-4 col-md-4 col-sm-12 col-xs-12 inner">
        <div id="logo"><a href="{{url('/')}}"><img class="img-responsive" src="http://demo.harnishdesign.net/html/marketshop/v3/image/logo.png" title="MarketShop" alt="MarketShop" /></a></div>
      </div>
      <!-- Logo End -->
      <!-- Search Start-->
      <div class="col-table-cell col-lg-5 col-md-5 col-md-push-0 col-sm-6 col-sm-push-6 col-xs-12">
        <div id="search" class="input-group">
        <form action="{{ url('web/search') }}" method="get">
          {{ csrf_field() }}
          <input id="filter_name" type="text" name="search" value="@if(isset($_GET['search'])) {{$_GET['search']}} @endif" placeholder="Search" class="form-control input-lg" />
          <button type="submit" class="button-search"><i class="fa fa-search"></i></button>
        </form>
        </div>
      </div>
      <!-- Search End-->
      <!-- Mini Cart Start-->
      @include('frontend.components.cart.mini-cart')
      <!-- Mini Cart End-->
    </div>
  </div>
</header>