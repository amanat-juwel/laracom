<nav id="top" class="htop">
  <div class="container">
    <div class="row"> <span class="drop-icon visible-sm visible-xs"><i class="fa fa-align-justify"></i></span>
      <div class="pull-left flip left-top">
        <div class="links">
          <ul>
            <li class="mobile"><i class="fa fa-phone"></i>{{$globalSettings->mobile}}</li>
            <li class="email"><a href="mailto:info@marketshop.com"><i class="fa fa-envelope"></i>{{$globalSettings->email}}</a></li>
            <!-- <li class="wrap_custom_block hidden-sm hidden-xs"><a>Custom Block<b></b></a>
              <div class="dropdown-menu custom_block">
                <ul>
                  <li>
                    <table>
                      <tbody>
                        <tr>
                          <td><img alt="" src="http://demo.harnishdesign.net/html/marketshop/v3/image/banner/cms-block.jpg"></td>
                          <td><img alt="" src="http://demo.harnishdesign.net/html/marketshop/v3/image/banner/responsive.jpg"></td>
                        </tr>
                        <tr>
                          <td><h4>CMS Blocks</h4></td>
                          <td><h4>Responsive Template</h4></td>
                        </tr>
                        <tr>
                          <td>This is a CMS block. You can insert any content (HTML, Text, Images) Here.</td>
                          <td>This is a CMS block. You can insert any content (HTML, Text, Images) Here.</td>
                        </tr>
                        <tr>
                          <td><strong><a class="btn btn-default btn-sm" href="#">Read More</a></strong></td>
                          <td><strong><a class="btn btn-default btn-sm" href="#">Read More</a></strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </li>
                </ul>
              </div>
            </li> -->
            @if(Auth::user()!==null)
            <li><a href="{{url('web/wishlist')}}">Wish List ({{$wishlist_count}})</a></li>
            @endif
            <li><a href="{{url('web/checkout')}}">Checkout</a></li>
          </ul>
        </div>
        <!-- <div id="language" class="btn-group">
          <button class="btn-link dropdown-toggle" data-toggle="dropdown"> <span> <img src="http://demo.harnishdesign.net/html/marketshop/v3/image/flags/gb.png" alt="English" title="English">English <i class="fa fa-caret-down"></i></span></button>
          <ul class="dropdown-menu">
            <li>
              <button class="btn btn-link btn-block language-select" type="button" name="GB"><img src="http://demo.harnishdesign.net/html/marketshop/v3/image/flags/gb.png" alt="English" title="English" /> English</button>
            </li>
            <li>
              <button class="btn btn-link btn-block language-select" type="button" name="GB"><img src="http://demo.harnishdesign.net/html/marketshop/v3/image/flags/ar.png" alt="Arabic" title="Arabic" /> Arabic</button>
            </li>
          </ul>
        </div> -->
        <!-- <div id="currency" class="btn-group">
          <button class="btn-link dropdown-toggle" data-toggle="dropdown"> <span> $ USD <i class="fa fa-caret-down"></i></span></button>
          <ul class="dropdown-menu">
            <li>
              <button class="currency-select btn btn-link btn-block" type="button" name="EUR">€ Euro</button>
            </li>
            <li>
              <button class="currency-select btn btn-link btn-block" type="button" name="GBP">£ Pound Sterling</button>
            </li>
            <li>
              <button class="currency-select btn btn-link btn-block" type="button" name="USD">$ US Dollar</button>
            </li>
          </ul>
        </div> -->
      </div>
      <div id="top-links" class="nav pull-right flip">
        <ul>
          @if(Auth::user() !== null)
          <li class="dropdown" id="my_account"><a href="#">My Account <i class="fa fa-caret-down"></i></a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="{{url('web/my-account')}}">My Account</a></li>
              <li><a href="{{url('web/order-history')}}">Order History</a></li>
              <!--<li><a href="{{url('web/order-information')}}">Order Information</a></li>-->
            </ul>
          </li>
          @endif
          @if(Auth::user() == null)
          <li><a href="{{ url('/login') }}">Login </a></li>
          <li><a href="{{ url('/register') }}">Register</a></li>
          @else
          <li><a href="{{ url('web/users/logout') }}">Logout </a></li>
          @endif
          
        </ul>
      </div>
    </div>
  </div>
</nav>