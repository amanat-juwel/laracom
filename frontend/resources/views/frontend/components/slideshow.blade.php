<div class="slideshow single-slider owl-carousel">
    @foreach($sliders as $key => $data)
        <div class="item"> <a href="#"><img class="img-responsive" src='{{ asset("$data->image") }}' alt="banner 1" /></a></div>
    @endforeach
</div>