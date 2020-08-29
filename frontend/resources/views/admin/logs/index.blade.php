@extends('admin.layouts.template')

@section('template')
<style type="text/css">
    tr,td,th{
        border: 1px solid black;
        padding: 3px;
    }
</style>
<section class="content-header">
    <h1>
        LOGS
        
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Log</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-12">
        <section class="content">
            <div class="panel panel-success">
                <div class="panel-heading">
                    List
                </div>
                <div class="panel-body">
                    <table class="" id="" width="100%">
                        <thead>
                            <tr>
                                <th height="25">Srl</th>
                                <th>Date-Time</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($logs))
                                @foreach($logs as $key => $data)
                                <tr>
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ date('Y-m-d h:i:s A',strtotime($data->date_time)) }}</td>
                                    <td>{{ $data->type }}</td>
                                    <td>{{ $data->description }}</td>
                                    <td>{{ $data->user }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- End Main Content -->
@endsection