
@if($sidebar_sliders->count() > 0)
<div class="banner owl-carousel">
	@foreach($sidebar_sliders as $key => $data)
	<div class="item"> 
		<a href="#"><img src='{{ asset("$data->image") }}' alt="banner" class="img-responsive" /></a> 
	</div>
	@endforeach
</div>
@endif