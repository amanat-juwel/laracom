<h3 class="subtitle">Categories</h3>
<div class="box-category">
  <ul id="cat_accordion">
    @for($i=0; $i < count($all_categories_menu); $i++) 
    <li> <a href="{{url('web/categories/'.$all_categories_menu[$i][0]['category'])}}">{{$all_categories_menu[$i][0]['category']}}</a> @if(count($all_categories_menu[$i][1])>0)<span class="down"></span>@endif
      @if(count($all_categories_menu[$i][1])>0)
      <ul>
        @for ($j=0; $j < count($all_categories_menu[$i][1]); $j++) 
        <li><a href="">{{$all_categories_menu[$i][1][$j][0]['sub_category']}}</a> @if(count($all_categories_menu[$i][1][$j][1])>0)<span class="down"></span>@endif
          @if(count($all_categories_menu[$i][1][$j][1])>0)
          <ul>
            @for ($k=0; $k < count($all_categories_menu[$i][1][$j][1]); $k++) 
            <li><a href="">{{$all_categories_menu[$i][1][$j][1][$k]['sub_sub_name']}}</a></li>
            @endfor
          </ul>
          @endif
        </li>
        @endfor
      </ul>
      @endif
    </li>
    @endfor
  </ul>
</div>