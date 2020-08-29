@extends('admin.layouts.template')

@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
         BATCH
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Batch</li>
        <li class="active">Edit</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                
                <div class="panel-body">
                    <form class="form" action="{{ url('admin/batch/update') }}" method="post" name="edit">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $batch->id }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Batch Name *</label>
                            <input autocomplete="OFF" type="text"  name="batch_name" placeholder="Batch Name" value="{{$batch->batch_name}}" class="form-control input-sm" required/>
                            </div>
                          
                            <div class="form-group">
                                <label for="">Select item</label>
                                <select name="item_id" id="item_id" class="form-control select2 input-sm">
                                    <option>--Select--</option>
                                    @foreach($items as $data)
                                    <option value="{{$data->item_id}}" >{{$data->item_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Purchase Rate</label>
                                <input autocomplete="OFF" type="text"  name="rate" value="{{$batch->purchase_rate}}" placeholder="Rate" class="form-control input-sm" />
                            </div>
                         

                            <div class="form-group pull-right">
                                <button type="submit" name="action" value="save" class="btn btn-success">Save Changes</button>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </form>
                </div>
            </div>
            @if(Session::has('success'))
            <div class="alert alert-success" id="success">
                {{Session::get('success')}}
                @php
                Session::forget('success');
                @endphp
            </div>
            @endif
        </div>
    </div>
</section>

<script type="text/javascript">

document.forms['edit'].elements['item_id'].value={{$batch->item_id}}

</script>
@endsection