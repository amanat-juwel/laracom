
          <h3 class="subtitle">Bestsellers</h3>
          <div class="side-item">
            @foreach($related_items as $item)
            <div class="product-thumb clearfix">
              <div class="image"><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}"><img src="{{asset($item->item_image)}}" alt="{{asset($item->item_name)}}" class="img-responsive" /></a></div>
              <div class="caption">
                <h4><a href="{{url('web/products/'.$item->item_id.'/'.$item->item_name)}}">{{$item->item_name}}</a></h4>
                <p class="price"><span class="price-new">@if(empty($item->discounted_price)){{number_format($item->mrp,0)}}@else{{number_format($item->discounted_price,0)}}@endif ৳</span> </p>
              </div>
            </div>
            @endforeach
          </div>
          <!-- <div class="list-group">
            <h3 class="subtitle">Custom Content</h3>
            <p>This is a CMS block edited from admin. You can insert any content (HTML, Text, Images) Here. </p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
            <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
          </div> -->
