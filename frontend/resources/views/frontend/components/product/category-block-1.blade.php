<div class="category-module" id="latest_category">
            <h3 class="subtitle">{{$home_view_block_1_category->cata_name}} - <a class="viewall" href="{{url('web/categories/'.$home_view_block_1_category->cata_name)}}">view all</a></h3>
            <div class="category-module-content">
              <ul id="sub-cat" class="tabs">
                @foreach($home_view_block_1_subcategories as $key=>$data)
                <li><a href="#tab-cat{{$key}}">{{$data->name}}</a></li>
                @endforeach
              </ul>
              @foreach($home_view_block_1_subcategories as $key=>$data)
              <div id="tab-cat{{$key}}" class="tab_content">
                <div class="owl-carousel latest_category_tabs">
                  @foreach($sub_sub_block_1_category_items as $item)
                  @if($item->sub_cata_id == $data->id)
                  <div class="product-thumb">
                    @include('frontend.components.product.unit-product')
                  </div>
                  @endif
                  @endforeach
                </div>
              </div>
              @endforeach
              
            </div>
          </div>