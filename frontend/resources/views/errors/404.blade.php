@extends('frontend.layouts.master')

@section('meta_info')
@endsection

@section('title')
404
@endsection

@section('style')
@endsection

@section('page-class')
bg-white
@endsection

@section('content')
<!-- inner page banner -->
        <div class="dlab-bnr-inr overlay-black-middle" style="background-image:url({{asset('public/frontend')}}/images/banner/bnr1.jpg);">
            <div class="container">
                <div class="dlab-bnr-inr-entry">
                    <h1 class="text-white">Error 404</h1>
                    <!-- Breadcrumb row -->
                    <div class="breadcrumb-row">
                        <ul class="list-inline">
                            <li><a href="#">Home</a></li>
                            <li>Error 404</li>
                        </ul>
                    </div>
                    <!-- Breadcrumb row END -->
                </div>
            </div>
        </div>
        <!-- inner page banner END -->
        <!-- Error Page -->
        <div class="section-full content-inner-3 error-page" style="background-image:url({{asset('public/frontend')}}/images/background/bg5.jpg); background-size:cover;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 m-b30 align-self-center text-center">
                        <h2 class="dz_error text-secondry">404</h2>
                        <h3>OOPS!</h3>
                        <h4>Page Not Found</h4>
                        <a href="{{ url('/') }}" class="site-button">Back To Home</a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <img src="{{asset('public/frontend')}}/images/collage.png" alt=""/>
                    </div>
                </div>
            </div>
        </div>
        <!-- Error Page END -->


@endsection

@section('script')
@endsection