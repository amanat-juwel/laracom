@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        ROLES 
        
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="#"><i class="fa fa-dashboard"></i> Home</a>
        </li>
        <li class="active">Dashboard</li>
        <li class="active">Roles</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
           <div class="panel panel-primary">
              <div class="panel-heading">
                 <a class="btn btn-default btn-sm" href="{{ url('admin/roles/create')  }}"><i class="fa fa-plus"></i> Add New</a>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                      <table class="table-bordered" id="purchase_details" width="100%">
                            <thead>
                                <tr>
                                    <th height="25">Srl</th>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $key => $data)
                                <tr id="">
                                    <td height="25">{{ ++$key }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->display_name }}</td>
                                    <td>
                                        <a class="btn btn-warning btn-xs" href="{{ url('admin/roles/'.$data->role_id.'/edit') }}"><i class="fa fa-edit"></i> Edit</a>

                                        <form action="{{ url('admin/roles/'.$data->role_id) }}" method="post" style="display:inline-block">
                                            {{ method_field('DELETE') }} {{ csrf_field() }}
                                            <button title="Delete" class="delete btn btn-danger btn-xs"  onclick="return confirm('Are you sure you want to delete this item?');" >
                                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(Session::has('success'))
                    <div class="alert alert-success" id="success">
                        {{Session::get('success')}} @php Session::forget('success'); @endphp
                    </div>
                    @endif @if(Session::has('update'))
                    <div class="alert alert-warning" id="update">
                        {{Session::get('update')}} @php Session::forget('update'); @endphp
                    </div>
                    @endif @if(Session::has('delete'))
                    <div class="alert alert-danger" id="delete">
                        {{Session::get('delete')}} @php Session::forget('delete'); @endphp
                    </div>
                    @endif
              </div>
            </div>
        </div>
    </div> 
      
</section>
<!-- End Main Content -->


@endsection