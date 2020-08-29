@extends('admin.layouts.template')
@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>BARCODE GENERATOR</h1>
     
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Barcode Generator</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        @if(Session::has('success'))
                        <div class="alert alert-success" id="success">
                            {{Session::get('success')}}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('update'))
                        <div class="alert alert-warning" id="update">
                            {{Session::get('update')}}
                            @php
                            Session::forget('update');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('delete'))
                        <div class="alert alert-danger" id="delete">
                            {{Session::get('delete')}}
                            @php
                            Session::forget('delete');
                            @endphp
                        </div>
                        @endif
                        <div class="panel panel-primary">
                          <div class="panel-heading">
                            List Purchase Invoice
                          </div>
                          <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form class="form" action="{{ url('/barcode') }}" id="" method="post" target="_blank">
                                        {!! csrf_field() !!}
                                        <input type="submit" class="btn btn-success btn-sm" value="Click here to Generate">
                                    </form>
                                </div>
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