          <h3 class="subtitle">{{$home_view_block_2_category->cata_name}}</h3>
          <div class="owl-carousel product_carousel">
            @foreach($sub_sub_block_2_category_items as $item)
            <div class="product-thumb clearfix">
              @include('frontend.components.product.unit-product')
            </div>
            @endforeach
          </div>