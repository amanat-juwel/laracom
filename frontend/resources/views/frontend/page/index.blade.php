@extends('frontend.layouts.master')

@section('title',$page->title)

@section('body')
<div id="container">
    <div class="container">
      <div class="row">
        <!--Middle Part Start-->
        <div id="content" class="col-sm-12">
          <h2 class="text-uppercase m-b0">{{$page->title}}</h2>
          <p class="font-18">{{$page->excerpt}}</p>
          {!!$page->body!!}
        </div>
        <!--Middle Part End -->
      </div>
    </div>
  </div>
@endsection