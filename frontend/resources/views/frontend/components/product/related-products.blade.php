          <h3 class="subtitle">Related Products</h3>
            <div class="owl-carousel related_pro">

              @foreach($related_items as $item)
              <div class="product-thumb clearfix">
                @include('frontend.components.product.unit-product')
              </div>
              @endforeach

            </div>