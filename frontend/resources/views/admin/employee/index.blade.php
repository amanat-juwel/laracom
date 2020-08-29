@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
     <h1>Executive</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Executive</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
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
                        <a href="{{ url('/human-resource/create') }}" class="btn btn-default" ><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                        <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th style="" height="25">Srl</th>
                                    <th style="">Name</th>
                                    <th style="">Department </th>
                                    <th style="">Designation </th>
                                    <th style="">Email </th>
                                    <th style="">Phone</th>
                                    <th style="">Address</th>
                                    <th style="">NID</th>
                                    <th style="">Joining Date</th>

                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($employees))
                                    @foreach($employees as $key => $employee)
                                    <tr>
                                        <td height="25">{{ ++$key }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->department }}</td>
                                        <td>{{ $employee->designation }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->phone }}</td>
                                        <td>{{ $employee->address }}</td>
                                        <td>{{ $employee->nid }}</td>
                                        <td>{{ $employee->joining_date }}</td>

                                        <td  style="text-align: center;">
                                            <!-- <a href="{{ url('/HR/'.$employee->id.'/edit') }}" class="edit  btn-success"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a> -->  
                                            <form action="{{ url('/human-resource/'.$employee->id) }}" method="post" style="display:inline-block">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button class="delete btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this HR?');"><i class="fa fa-trash-o"></i> Delete</button>
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
</section>
<!-- End Main Content -->
@endsection