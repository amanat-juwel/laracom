@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        BLOG
        <a href="{{ url('/blogs/'.$blog->id.'/edit') }}" class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('/blogs')}}">Blog</a></li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Author</h3>
                </div>
                <div class="box-body">
                    {{ $blog->authorInfo->name }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Title</h3>
                </div>
                <div class="box-body">
                    {{ $blog->title }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Excerpt</h3>
                </div>
                <div class="box-body">
                    {{ $blog->excerpt }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Body</h3>
                </div>
                <div class="box-body">
                    {!! $blog->body !!}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Post Image</h3>
                </div>
                <div class="box-body">
                  @if(!empty($blog->image))
                    <a href='{{url("$blog->image")}}' target="_blank">
                      <img src='{{ asset("$blog->image") }}' height="50%" width="50%">
                    </a>
                  @endif
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Post Video</h3>
                </div>
                <div class="box-body">
                  @if(!empty($blog->video))
                  <video width="320" height="240" controls>
                    <source src='{{ asset("$blog->video") }}' type="video/mp4">
                  </video>
                  @endif
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Slug</h3>
                </div>
                <div class="box-body">
                    {{ $blog->slug }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Meta Description</h3>
                </div>
                <div class="box-body">
                    {{ $blog->meta_description }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Meta Keywords</h3>
                </div>
                <div class="box-body">
                    {{ $blog->meta_keywords }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">SEO Title</h3>
                </div>
                <div class="box-body">
                    {{ $blog->seo_title }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Featured</h3>
                </div>
                <div class="box-body">
                    @if($blog->featured==1) Yes @else No @endif
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Category</h3>
                </div>
                <div class="box-body">
                    {{ $blog->postCategory->name }}
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                  <i class="fa fa-text-width"></i>
                  <h3 class="box-title">Created At</h3>
                </div>
                <div class="box-body">
                    {{ $blog->created_at }}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection