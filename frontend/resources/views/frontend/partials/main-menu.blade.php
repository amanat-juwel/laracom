<nav id="menu" class="navbar">
    <div class="container">
      <div class="navbar-header"> <span class="visible-xs visible-sm"> Menu <b></b></span></div>
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li><a class="home_link" title="Home" href="{{url('/')}}"><span>Home</span></a></li>
          <li class="dropdown"><a>Shop by Categories</a>
            <div class="dropdown-menu">
              <ul>
                @for($i=0; $i < count($all_categories_menu); $i++) 
                <li> <a href="{{ url('web/categories/'.$all_categories_menu[$i][0]['category']) }}">{{$all_categories_menu[$i][0]['category']}}@if(count($all_categories_menu[$i][1])>0)<span>&rsaquo;</span>@endif</a>
                  
                  @if(count($all_categories_menu[$i][1])>0)
                  <div class="dropdown-menu">
                    <ul>
                      @for ($j=0; $j < count($all_categories_menu[$i][1]); $j++) 
                      <li><a href="{{ url('web/categories/'.$all_categories_menu[$i][1][$j][0]['sub_category']) }}">{{$all_categories_menu[$i][1][$j][0]['sub_category']}}@if(count($all_categories_menu[$i][1][$j][1])>0)<span>&rsaquo;</span>@endif</a>
                        @if(count($all_categories_menu[$i][1][$j][1])>0)
                        <div class="dropdown-menu">
                          <ul>
                            @for ($k=0; $k < count($all_categories_menu[$i][1][$j][1]); $k++) 
                            <li><a href="{{ url('web/categories/'.$all_categories_menu[$i][1][$j][1][$k]['sub_sub_name']) }}">{{$all_categories_menu[$i][1][$j][1][$k]['sub_sub_name']}}</a></li>
                            @endfor
                          </ul>
                        </div>
                        @endif
                      </li>
                      @endfor
                    </ul>
                  </div>
                  @endif

                </li>
                @endfor
                
              </ul>
            </div>
          </li>
          <li class="menu_brands dropdown"><a href="#">Brands</a>
            <div class="dropdown-menu">
              @foreach($brands_front_view as $brand)
              <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6"><a href="{{url('web/brands/'.$brand->brand_name)}}"><img src="{{asset($brand->brand_image)}}" title="{{$brand->brand_name}}" alt="{{$brand->brand_name}}" /></a><a href="{{url('web/brands/'.$brand->brand_name)}}">{{$brand->brand_name}}</a></div>
              @endforeach
            </div>
          </li>

          <!-- <li class="dropdown wrap_custom_block hidden-sm hidden-xs"><a>Custom Block</a>
            <div class="dropdown-menu custom_block">
              <ul>
                <li>
                  <table>
                    <tbody>
                      <tr>
                        <td><img alt="" src="http://demo.harnishdesign.net/html/marketshop/v3/image/banner/cms-block.jpg"></td>
                        <td><img alt="" src="http://demo.harnishdesign.net/html/marketshop/v3/image/banner/responsive.jpg"></td>
                        <td><img alt="" src="http://demo.harnishdesign.net/html/marketshop/v3/image/banner/cms-block.jpg"></td>
                      </tr>
                      <tr>
                        <td><h4>CMS Blocks</h4></td>
                        <td><h4>Responsive Template</h4></td>
                        <td><h4>Dedicated Support</h4></td>
                      </tr>
                      <tr>
                        <td>This is a CMS block. You can insert any content (HTML, Text, Images) Here.</td>
                        <td>This is a CMS block. You can insert any content (HTML, Text, Images) Here.</td>
                        <td>This is a CMS block. You can insert any content (HTML, Text, Images) Here.</td>
                      </tr>
                      <tr>
                        <td><strong><a class="btn btn-primary btn-sm" href="#">Read More</a></strong></td>
                        <td><strong><a class="btn btn-primary btn-sm" href="#">Read More</a></strong></td>
                        <td><strong><a class="btn btn-primary btn-sm" href="#">Read More</a></strong></td>
                      </tr>
                    </tbody>
                  </table>
                </li>
              </ul>
            </div>
          </li> -->
          <!-- <li class="dropdown"><a href="http://demo.harnishdesign.net/html/marketshop/v3/blog.html">Blog</a>
            <div class="dropdown-menu">
            <ul>
            <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/blog.html">Blog</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/blog-grid.html">Blog Grid</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/blog-detail.html">Single Post</a></li>
            </ul>
            </div>
          </li> -->
          <!-- <li class="dropdown"><a>Pages</a>
            <div class="dropdown-menu">
              <ul>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/category.html">Category (Grid/List)</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/product.html">Product Page</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/cart.html">Shopping Cart</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/checkout.html">Checkout</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/compare.html">Product Compare</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/wishlist.html">Wishlist</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/search.html">Search</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/manufacturer.html">Brands</a></li>
              </ul>
              <ul>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/about-us.html">About Us</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/elements.html">Elements</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/elements-forms.html">Forms</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/careers.html">Careers</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/faq.html">Faq</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/404.html">404</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/sitemap.html">Sitemap</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/contact-us.html">Contact Us</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/email-template" target="_blank">Email Template Page</a></li>
              </ul>                
              <ul>
            <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/login.html">Login</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/register.html">Register</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/my-account.html">My Account</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/order-history.html">Order History</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/order-information.html">Order Information</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/return.html">Return</a></li>
                <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/gift-voucher.html">Gift Voucher</a></li>
            </ul>
            </div>
          </li> -->
          
          <!-- <li><a href="#">Blog</a></li> -->
          <li class="custom-link-right"><a href="#" target="#">Special Offers</a></li>
        </ul>
      </div>
    </div>
  </nav>