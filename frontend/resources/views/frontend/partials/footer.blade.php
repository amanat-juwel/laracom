<footer id="footer">
    <div class="fpart-first">
      <div class="container">
        <div class="row">
          <div class="contact col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <h5>Contact Details</h5>
            <ul>
              <li class="address"><i class="fa fa-map-marker"></i>{!!$globalSettings->address!!}</li>
              <li class="mobile"><i class="fa fa-phone"></i>{{$globalSettings->mobile}}</li>
              <li class="email"><i class="fa fa-envelope"></i>Send email via our <a href="{{url('web/contact-us')}}">Contact Us</a>
            </ul>
          </div>
          <div class="column col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h5>Information</h5>
            <ul>
              @foreach($pages_front_view as $page)
              <li class="no-padding">
                <li><a href="{{url('web/pages/'.$page->slug)}}">{{ $page->title }}</a></li>
              @endforeach
            </ul>
          </div>
          <div class="column col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h5>Customer Service</h5>
            <ul>
              <li><a href="{{url('web/contact-us')}}">Contact Us</a></li>
              <!-- <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/returns.html">Returns</a></li>
              <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/sitemap.html">Site Map</a></li> -->
            </ul>
          </div>
          <div class="column col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h5>Extras</h5>
            <ul>
              <li><a href="{{url('web/brands')}}">Brands</a></li>
              <!-- <li><a href="http://demo.harnishdesign.net/html/marketshop/v3/gift-voucher.html">Gift Vouchers</a></li>
              <li><a href="#">Affiliates</a></li>
              <li><a href="#">Specials</a></li> -->
            </ul>
          </div>
          <!-- <div class="column col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h5>Newsletter</h5>
            <div class="form-group">
            <label class="control-label" for="subscribe">Sign up to receive latest news and updates.</label>
            <input id="signup" type="email" required="" placeholder="Email address" name="email" class="form-control">
            </div>
            <input type="submit" value="Subscribe" class="btn btn-primary">
          </div> -->
        </div>
      </div>
    </div>
    <div class="fpart-second">
      <div class="container">
        <div id="powered" class="clearfix">
          <div class="powered_text pull-left flip">
            <p>Coptright © {{ date('Y') }} | {{ $globalSettings->company_name }}</p>
          </div>
          <!-- <div class="social pull-right flip"> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/socialicons/facebook.png" alt="Facebook" title="Facebook"></a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/socialicons/twitter.png" alt="Twitter" title="Twitter"> </a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/socialicons/google_plus.png" alt="Google+" title="Google+"> </a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/socialicons/pinterest.png" alt="Pinterest" title="Pinterest"> </a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/socialicons/rss.png" alt="RSS" title="RSS"> </a> </div> -->

          <div class="payments_types pull-right"> 
            @foreach($frontend_payment_methods as $data)
            <img data-toggle="tooltip" src="{{asset('public/frontend/images/payment-method/'.$data->logo)}}" alt="paypal" title="{{$data->name}}">
            @endforeach
          </div>
        </div>
<!--         <div class="bottom-row">
          <div class="custom-text text-center"> <img alt="" src="http://demo.harnishdesign.net/html/marketshop/v3/image/logo-small.png">
            <p>This is a CMS block. You can insert any content (HTML, Text, Images) Here. Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
          </div>
          <div class="payments_types"> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/payment/payment_paypal.png" alt="paypal" title="PayPal"></a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/payment/payment_american.png" alt="american-express" title="American Express"></a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/payment/payment_2checkout.png" alt="2checkout" title="2checkout"></a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/payment/payment_maestro.png" alt="maestro" title="Maestro"></a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/payment/payment_discover.png" alt="discover" title="Discover"></a> <a href="#" target="_blank"> <img data-toggle="tooltip" src="http://demo.harnishdesign.net/html/marketshop/v3/image/payment/payment_mastercard.png" alt="mastercard" title="MasterCard"></a> </div>
        </div> -->
      </div>
    </div>
    <div id="back-top"><a data-toggle="tooltip" title="Back to Top" href="javascript:void(0)" class="backtotop"><i class="fa fa-chevron-up"></i></a></div>
    </footer>