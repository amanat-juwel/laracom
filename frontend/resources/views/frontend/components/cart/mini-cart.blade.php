<div class="col-table-cell col-lg-3 col-md-3 col-md-pull-0 col-sm-6 col-sm-pull-6 col-xs-12 inner">
        <div id="cart">
          <button type="button" data-toggle="dropdown" data-loading-text="Loading..." class="heading dropdown-toggle"> <span class="cart-icon pull-left flip"></span> <span id="cart-total">{{Cart::count()}} item(s) - {{Cart::subtotal()}}৳</span></button>
          <ul class="dropdown-menu">
            <li>
              <table class="table">
                <tbody>
                  @foreach(Cart::content() as $cart)
                  <tr>
                    <!-- <td class="text-center"><a href="{{url('web/products/'.$cart->id.'/'.$cart->name)}}"><img class="img-thumbnail" title="{{$cart->name}}" alt="{{$cart->name}}" src="http://demo.harnishdesign.net/html/marketshop/v3/image/product/sony_vaio_1-50x50.jpg"></a></td> -->
                    <td class="text-left" style="width: 50%"><a href="{{url('web/products/'.$cart->id.'/'.$cart->name)}}">{{$cart->name}}</a></td>
                    <td class="text-right">{{$cart->qty}} x </td>
                    <td class="text-right">{{$cart->price}}৳</td>
                    <td class="text-center"><form action="{{ url('/web/cart/'.$cart->rowId) }}" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" data-toggle="tooltip" title="Remove" class="btn btn-danger btn-xs remove">
                            <i class="fa fa-times" aria-hidden="true"> </i></button>
                        </form></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </li>
            <li>
              <div>
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td class="text-right"><strong>Sub-Total</strong></td>
                      <td class="text-right">{{Cart::subtotal()}}৳</td>
                    </tr>
                    <!-- <tr>
                      <td class="text-right"><strong>Eco Tax (-2.00)</strong></td>
                      <td class="text-right">$4.00</td>
                    </tr> -->
                    <!-- <tr>
                      <td class="text-right"><strong>VAT (20%)</strong></td>
                      <td class="text-right">$188.00</td>
                    </tr> -->
                    <tr>
                      <td class="text-right"><strong>Total</strong></td>
                      <td class="text-right">{{Cart::subtotal()}}৳</td>
                    </tr>
                  </tbody>
                </table>
                <p class="checkout"><a href="{{url('web/cart')}}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> View Cart</a>&nbsp;&nbsp;&nbsp;<a href="{{url('web/checkout')}}" class="btn btn-primary"><i class="fa fa-share"></i> Checkout</a></p>
              </div>
            </li>
          </ul>
        </div>
      </div>