@extends('frontend.layouts.master')

@section('title',$item->item_name)

@section('style')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<style>
    .swal2-select{
        display: none !important;
    }
</style>
@endsection

@section('body')

<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{{url('/')}}" itemprop="url"><span itemprop="title"><i class="fa fa-home"></i></span></a></li>
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="" itemprop="url"><span itemprop="title">{{ $item->cata_name }}</span></a></li>
        @if(!empty($item->sub_cata_name))
          <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="" itemprop="url"><span itemprop="title">{{ $item->sub_cata_name }}</span></a></li>
        @endif
        @if(!empty($item->sub_sub_cata_name))
          <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="" itemprop="url"><span itemprop="title">{{ $item->sub_sub_cata_name }}</span></a></li>
        @endif
        <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}" itemprop="url"><span itemprop="title">{{$item->item_name}}</span></a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
      <!--Right Part Start -->
        <aside id="column-left" class="col-sm-3 hidden-xs">
        <!-- include('frontend.components.product.bestsellers')
        include('frontend.components.product.specials') -->
        @include('frontend.components.banner')
        </aside>
        <!--Right Part End -->
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
          <div itemscope itemtype="http://schema.org/Product">
            <h1 class="title" itemprop="name">{{$item->item_name}}</h1>
            <div class="row product-info">
              <div class="col-sm-6">
                <div class="image"><img class="img-responsive" itemprop="image" id="zoom_01" src="{{asset($item->item_image)}}" title="{{$item->item_name}}" alt="{{$item->item_name}}" /> </div>
                <div class="center-block text-center"></div>
               
              </div>
              <div class="col-sm-6">
                <ul class="list-unstyled description">
                  <li><b>Brand:</b> <a href="#"><span itemprop="brand">{{ $item->brand_name }}</span></a></li>
                  <li><b>Product Code:</b> <span itemprop="mpn">{{ str_pad($item->item_code, 4, '0', STR_PAD_LEFT) }}</span></li>
                  <!-- <li><b>Reward Points:</b> 700</li> -->
                  <li><b>Availability:</b> @if($stock_qty>0)<span class="instock">In Stock</span>@else <span class="nostock">Out of Stock</span> @endif</li>
                </ul>
                <ul class="price-box">
                  <li class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span class="price-old">@if(!empty($item->discounted_price)){{number_format($item->mrp,2).'৳'}}@endif</span> <span itemprop="price">@if(empty($item->discounted_price)) {{number_format($item->mrp,0).'৳'}}@else{{number_format($item->discounted_price,0).'৳'}}@endif<span itemprop="availability" content="In Stock"></span></span></li>
                  <!-- <li></li> -->
                  <!-- <li>Ex Tax: $950.00</li> -->
                </ul>
                <div id="product">
                  <!-- <h3 class="subtitle">Available Options</h3>
                  <div class="form-group required">
                    <label class="control-label">Color</label>
                    <select class="form-control" id="input-option200" name="option[200]">
                      <option value=""> --- Please Select --- </option>
                      <option value="4">Black </option>
                      <option value="3">Silver </option>
                      <option value="1">Green </option>
                      <option value="2">Blue </option>
                    </select>
                  </div> -->
                  <div class="cart">
                    <div>
                      <form action="{{ url('web/add-to-cart') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="item_id" value="{{$item->item_id}}"/>
                        <input type="hidden" name="item_name" value="{{$item->item_name}}"/>
                        <input type="hidden" name="price" value="@if(empty($item->discounted_price)){{$item->mrp}}@else{{$item->discounted_price}}@endif"/>
                        <div class="qty">
                          <label class="control-label" for="input-quantity">Qty</label>
                          <input type="text" name="quantity" value="1" size="2" id="input-quantity" class="form-control" />
                          <a class="qtyBtn plus" href="javascript:void(0);">+</a><br />
                          <a class="qtyBtn mines" href="javascript:void(0);">-</a>
                          <div class="clear"></div>
                        </div>
                        <button type="submit" id="button-cart" class="btn btn-primary btn-lg">Add to Cart</button>
                      </form>
                    </div>
                    <div>
                      @if(Auth::user()!==null)
                      <button type="button" class="wishlist" id="wishlist_{{$item->item_id}}" onClick=""><i class="@if(!$is_wishlist)fa fa-heart-o @else fa fa-heart text-red @endif"></i> @if(!$is_wishlist)Add to Wish List @else Added to Wishlist @endif</button>
                      @endif
                      <!-- <br />
                      <button type="button" class="wishlist" onClick=""><i class="fa fa-exchange"></i> Compare this Product</button> -->
                    </div>
                  </div>
                </div>
                <!-- <div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                  <meta itemprop="ratingValue" content="0" />
                  <p><span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span> <a onClick="$('a[href=\'#tab-review\']').trigger('click'); return false;" href=""><span itemprop="reviewCount">1 reviews</span></a> / <a onClick="$('a[href=\'#tab-review\']').trigger('click'); return false;" href="">Write a review</a></p>
                </div> -->
                <!-- <hr> -->
                <!-- AddThis Button BEGIN -->
                <!-- <div class="addthis_toolbox addthis_default_style"> <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_google_plusone" g:plusone:size="medium"></a> <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal" pi:pinit:url="http://www.addthis.com/features/pinterest" pi:pinit:media="http://www.addthis.com/cms-content/images/features/pinterest-lg.png"></a> <a class="addthis_counter addthis_pill_style"></a> </div>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-514863386b357649"></script> -->
                <!-- AddThis Button END -->
              </div>
            </div>
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-description" data-toggle="tab">Description</a></li>
              <li><a href="#tab-specification" data-toggle="tab">Specification</a></li>
              <!-- <li><a href="#tab-review" data-toggle="tab">Reviews (2)</a></li> -->
            </ul>
            <div class="tab-content">
              <div itemprop="description" id="tab-description" class="tab-pane active">
                <div>
                  @if($item->description!=null)
                    {!!$item->description!!}
                  @else
                    <p>Product description not available</p>
                  @endif
                </div>
              </div>
              <div id="tab-specification" class="tab-pane">
                <div id="tab-specification" class="tab-pane">
                  @if($item->specification!=null)
                    {!!$item->specification!!}
                  @else
                    <p>Product specification not available</p>
                  @endif
              </div>
              </div>
              <!-- <div id="tab-review" class="tab-pane">
                <form class="form-horizontal">
                  <div id="review">
                    <div>
                      <table class="table table-striped table-bordered">
                        <tbody>
                          <tr>
                            <td style="width: 50%;"><strong><span>harvey</span></strong></td>
                            <td class="text-right"><span>20/01/2016</span></td>
                          </tr>
                          <tr>
                            <td colspan="2"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                              <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> </div></td>
                          </tr>
                        </tbody>
                      </table>
                      <table class="table table-striped table-bordered">
                        <tbody>
                          <tr>
                            <td style="width: 50%;"><strong><span>Andrson</span></strong></td>
                            <td class="text-right"><span>20/01/2016</span></td>
                          </tr>
                          <tr>
                            <td colspan="2"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                              <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> </div></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-right"></div>
                  </div>
                  <h2>Write a review</h2>
                  <div class="form-group required">
                    <div class="col-sm-12">
                      <label for="input-name" class="control-label">Your Name</label>
                      <input type="text" class="form-control" id="input-name" value="" name="name">
                    </div>
                  </div>
                  <div class="form-group required">
                    <div class="col-sm-12">
                      <label for="input-review" class="control-label">Your Review</label>
                      <textarea class="form-control" id="input-review" rows="5" name="text"></textarea>
                      <div class="help-block"><span class="text-danger">Note:</span> HTML is not translated!</div>
                    </div>
                  </div>
                  <div class="form-group required">
                    <div class="col-sm-12">
                      <label class="control-label">Rating</label>
                      &nbsp;&nbsp;&nbsp; Bad&nbsp;
                      <input type="radio" value="1" name="rating">
                      &nbsp;
                      <input type="radio" value="2" name="rating">
                      &nbsp;
                      <input type="radio" value="3" name="rating">
                      &nbsp;
                      <input type="radio" value="4" name="rating">
                      &nbsp;
                      <input type="radio" value="5" name="rating">
                      &nbsp;Good</div>
                  </div>
                  <div class="buttons">
                    <div class="pull-right">
                      <button class="btn btn-primary" id="button-review" type="button">Continue</button>
                    </div>
                  </div>
                </form>
              </div> -->
            </div>
            @include('frontend.components.product.related-products')
          </div>
        </div>
        <!--Middle Part End -->
        
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
  // START ADD TO FAVOURITE LIST
$(document).ready(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // like and unlike click
    $(".wishlist").click(function(){
        var id = this.id;   // Getting Button id
        var split_id = id.split("_");

        var item_id = split_id[1];  // item_id
        console.log(item_id)
        $.ajax({
            url:"{{url('web/add-to-wishlist')}}",
            type: 'post',
            data: {item_id:item_id},
            dataType: 'json',
            success: function(data){
              console.log(data)
                var message = data['message'];
                $("#wishlist_"+item_id).html(message);   
            }
            
        });

    });

});
// END ADD TO FAVOURITE LIST

@if(Session::has('success'))
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    Toast.fire({
      type: 'success',
      title: "{{Session::get('success')}}"
    })
  </script>
  @php Session::forget('success');@endphp
@endif

</script>
@endsection