<!DOCTYPE html>
<html>

<!-- http://demo.harnishdesign.net/html/marketshop/v3/index.html -->
<head>
<meta charset="UTF-8" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://demo.harnishdesign.net/html/marketshop/v3/image/favicon.png" rel="icon" />
<title>@yield('title')</title>
<meta name="description" content="Responsive and clean html template design for any kind of ecommerce webshop">
<!-- CSS Part Start-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('public/')}}/frontend/css/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/')}}/frontend/css/owl.carousel.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/')}}/frontend/css/owl.transitions.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/')}}/frontend/css/responsive.css" />
<link rel="stylesheet" type="text/css" href="{{asset('public/')}}/frontend/css/stylesheet-skin3.css" />
<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('style')
<!-- CSS Part End-->
</head>
<body>
<div class="wrapper-wide">
  <div id="header">
    <!-- Top Bar Start-->
    @include('frontend.partials.topbar')
    <!-- Top Bar End-->
    <!-- Header Start-->
    @include('frontend.partials.header')
    <!-- Header End-->
    <!-- Main Menu Start-->
    @include('frontend.partials.main-menu')
    <!-- Main Menu End-->
  </div>
  @yield('body')

  <!--Footer Start-->
  @include('frontend.partials.footer')
  <!--Footer End-->

</div>
<!-- JS Part Start-->
<script type="text/javascript" src="{{asset('public/')}}/frontend/js/jquery-2.1.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('public/')}}/frontend/js/jquery.easing-1.3.min.js"></script>
<script type="text/javascript" src="{{asset('public/')}}/frontend/js/jquery.dcjqaccordion.min.js"></script>
<script type="text/javascript" src="{{asset('public/')}}/frontend/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="{{asset('public/')}}/frontend/js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script> 
<script>
  /*START SUMMARNOTE*/
   $('#summernote').summernote({
   //height: 350, 
   //width: 1000,  //set editable area's height
   });
   //start code for custom note
   var append = '<div class="note-btn-group btn-group note-view"><button type="button" id="custom-img" data-toggle="modal" data-target="#myModal"><i class="note-icon-picture"></i></button></div>' ;
   $('.custom-note').find('.note-btn-group').last().append(append);
   $('#custom-img').click(function() {
    
  });
  /*END SUMMARNOTE*/
</script>
@yield('script')
<!-- JS Part End-->
</body>


</html>