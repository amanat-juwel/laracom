<div class="image"><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}"><img src="{{asset($item->item_image)}}" alt="iPhone5" title="iPhone5" class="img-responsive" /></a></div>
<div class="caption">
  <h4><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}">{{$item->item_name}}</a></h4>
  <p class="price"> <span class="price-new">@if(empty($item->discounted_price)){{number_format($item->mrp,0)}}@else{{number_format($item->discounted_price,0)}}@endif ৳</span> @if(!empty($item->discounted_price))<span class="price-old">{{number_format($item->mrp,2)}} ৳</span> <span class="saving">-{{number_format(100-(($item->discounted_price*100)/$item->mrp),0)}}%</span> @endif</p>
  <!-- <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> </div> -->
</div>
    <form action="{{ url('web/add-to-cart') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="item_id" value="{{$item->item_id}}"/>
    <input type="hidden" name="item_name" value="{{$item->item_name}}"/>
    <input type="hidden" name="price" value="@if(empty($item->discounted_price)){{$item->mrp}}@else{{$item->discounted_price}}@endif"/>
    <input type="hidden" name="quantity" value="1"/>
    <div class="button-group">
    <button class="btn-primary" type="submit" onClick=""><i class="fa fa-shopping-cart"></i> <span>Add to Cart</span></button>
    <!--<div class="add-to-links">-->
    <!--  <button type="button" data-toggle="tooltip" title="Add to Wish List" onClick=""><i class="fa fa-heart"></i> <span>Add to Wish List</span></button>-->
    <!--  <button type="button" data-toggle="tooltip" title="Compare this Product" onClick=""><i class="fa fa-exchange"></i> <span>Compare this Product</span></button>-->
    <!--</div>-->
  </div>
  </form>