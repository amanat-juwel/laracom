@extends('frontend.layouts.master')

@section('title',isset($category->cata_name) ? $category->cata_name : 'Search Result')

@section('body')

<div id="container">
    <div class="container">
      <!-- Breadcrumb Start-->
      <ul class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i></a></li>
        <li><a href="#">Category</a></li>
        <li><a href="#">{{isset($category->cata_name) ? $category->cata_name : 'Search Result'}}</a></li>
      </ul>
      <!-- Breadcrumb End-->
      <div class="row">
        <!--Left Part Start -->
        <aside id="column-left" class="col-sm-3 hidden-xs">
          @include('frontend.partials.category-menu')
          
          <!-- include('frontend.components.product.bestsellers')
        include('frontend.components.product.specials') -->
          @include('frontend.components.banner')

        </aside>
        <!--Left Part End -->
        <!--Middle Part Start-->
        <div id="content" class="col-sm-9">
          <h1 class="title">{{isset($category->cata_name) ? $category->cata_name : 'Search Result'}}</h1>
          <div class="product-filter">
            <div class="row">
              <div class="col-md-4 col-sm-5">
                <div class="btn-group">
                  <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="List"><i class="fa fa-th-list"></i></button>
                  <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Grid"><i class="fa fa-th"></i></button>
                </div>
                <!-- <a href="compare.html" id="compare-total">Product Compare (0)</a> --> </div>
              <!-- <div class="col-sm-2 text-right">
                <label class="control-label" for="input-sort">Sort By:</label>
              </div>
              <div class="col-md-3 col-sm-2 text-right">
                <select id="input-sort" class="form-control col-sm-3">
                  <option value="" selected="selected">Default</option>
                  <option value="">Name (A - Z)</option>
                  <option value="">Name (Z - A)</option>
                  <option value="">Price (Low &gt; High)</option>
                  <option value="">Price (High &gt; Low)</option>
                  <option value="">Rating (Highest)</option>
                  <option value="">Rating (Lowest)</option>
                  <option value="">Model (A - Z)</option>
                  <option value="">Model (Z - A)</option>
                </select>
              </div>
              <div class="col-sm-1 text-right">
                <label class="control-label" for="input-limit">Show:</label>
              </div>
              <div class="col-sm-2 text-right">
                <select id="input-limit" class="form-control">
                  <option value="" selected="selected">20</option>
                  <option value="">25</option>
                  <option value="">50</option>
                  <option value="">75</option>
                  <option value="">100</option>
                </select>
              </div> -->
            </div>
          </div>
          <br />
          <div class="row products-category">
            @foreach($items as $item)
            <div class="product-layout product-list col-xs-12">
              <div class="product-thumb">
                @include('frontend.components.product.unit-product')
              </div>
            </div>
            @endforeach
          </div>
          <div class="row">
            <div class="col-sm-6 text-left">
              {{$items->links()}}
            </div>
            <!-- <div class="col-sm-6 text-right">Showing 1 to 12 of 15 (2 Pages)</div> -->
          </div>
        </div>
        <!--Middle Part End -->
      </div>
    </div>
  </div>
@endsection