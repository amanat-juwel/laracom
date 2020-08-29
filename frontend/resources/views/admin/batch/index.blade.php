@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
         BATCH LIST
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
        <li class="active">Batch List</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
      
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a href="{{ url('admin/batch/create') }}" class="btn btn-default btn-sm" ><i class="fa fa-plus"></i> Add New </a>
                </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table-bordered" id="purchase_details" width="100%">
                                <thead>
                                    <tr>
                                        <th height="25">Srl</th>
                                        <th style="text-align:left">Batch</th>
                                        <th style="text-align:left"> Item</th>
                                        <th style="text-align:left"> Quantity</th>
                                        <!-- <th style="text-align:left">Type</th> -->
                                        <th style="text-align:left">Purchase Rate</th>
                                        <th style="text-align:left"> Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($batches))
                                        @foreach($batches as $key => $batch)
                                        <tr>
                                            <td height="25">{{ ++$key }}</td>
                                            <td>{{ $batch->code }}</td>
                                            <td>{{ $batch->item_name }}</td>
                                            <td>{{$batch->stock}}</td>
                                            <td>
                                                <span class="text-red">{{ $batch->purchase_rate }}</span>
                                            </td>
                                            @if($batch->stock > 0)
                                              <td><span class="label label-success">Active</span></td>
                                            @else
                                              <td><span class="label label-danger">InActive</span></td>
                                            @endif
                                            <td>
                                                <div style="display:flex;">

                                                    <a href="{{ url('admin/batch/'.$batch->id.'/edit') }}"><button class="edit btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </button></a>
                                                    &nbsp;&nbsp;&nbsp;
                                                  
                                                        <form action="{{ url('admin/batch/'.$batch->id.'/delete') }}" method="post">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button class="delete btn btn-danger btn-xs" onclick="return confirm('Do you want to delete?');" title="Delete">
                                                            <i class="fa fa-trash-o" aria-hidden="true"> </i></button>
                                                        </form>
                                                    
                                                </div>
                                               
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

</script>
@endsection