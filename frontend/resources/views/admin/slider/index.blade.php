@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>SLIDER</h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('/sliders')}}">Slider</a></li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.partials.message')
                    <div class="table-responsive">
                        <div class="panel panel-primary">
                          <div class="panel-heading">
                            <a href="{{ url('admin/sliders/create') }}" class="btn btn-default" ><i class="fa fa-plus"></i> Add New</a>
                          </div>
                          <div class="panel-body">
                              <div class="table-responsive">
                                <table class="table table-bordered" id="dataTables" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Srl</th>
                                            <th>Title</th>
                                            <th>Sub-Title</th>
                                            <th>Image</th>
                                            <th>Redirect To</th>
                                            <th>Order</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if(isset($sliders))
                                            @foreach($sliders as $key => $data)
                                            <tr>
                                                <td height="25">{{ ++$key }}</td>
                                                <td>{{ $data->title }}</td>
                                                <td>{{ $data->sub_title }}</td>
                                                <td>
                                                    @if(!empty($data->image))
                                                    <a href='{{url("$data->image")}}' target="_blank">
                                                        <img src='{{ asset("$data->image") }}' height="100" width="120">
                                                    </a>
                                                    @endif
                                                </td>
                                                <td>{{ $data->redirect_link }}</td>
                                                <td>{{ $data->slider_order }}</td>
                                                <td>@if($data->active==1) Active @else In-active @endif</td>
                                                <td>{{ $data->created_at }}</td>
                                                <td  style="text-align: center;">
                                                        <a href="{{ url('admin/sliders/'.$data->id.'/edit') }}" class="  btn btn-info btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                                       
                                                        <form action="{{ url('admin/sliders/'.$data->id) }}" method="post" style="display:inline-block">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</button>
                                                        </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                          </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Main Content -->
@endsection