<h3 class="subtitle">Newly Arrived</h3>
          <div class="owl-carousel product_carousel">
            @foreach($newly_arrived_items as $item)
            <div class="product-thumb clearfix">
              @include('frontend.components.product.unit-product')
            </div>
            @endforeach
          </div>