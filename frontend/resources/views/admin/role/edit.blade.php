@extends('admin.layouts.template')


@section('template')
<!-- Content Header -->
<section class="content-header">
    <h1>
        Add Role
        </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Role</li>
        <li class="active">Add Role</li>
    </ol>
</section>
<!-- End Content Header -->
<!-- Main content -->
<div class="row">
    <div class="col-md-6">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form" action="{{ url('roles/'.$role->role_id) }}" method="post" >
                                {{ method_field('PUT') }}
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" autocomplete="OFF" name="name" value="{{$role->name}}" class="form-control input-sm" />
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Display Name</label>
                                    <input type="text" autocomplete="OFF" name="display_name" value="{{$role->display_name}}" class="form-control input-sm" />
                                    @if($errors->has('display_name'))
                                        <span class="text-danger">{{ $errors->first('display_name')}}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="">Permissions</label><br>
                                    <a href="#" class="permission-select-all">Select All</a> / <a href="#"  class="permission-deselect-all">Deselect All</a><br>
                                    @foreach($tables as $singleTable)
                                    <ul class="permissions" type="none" style="padding-left: 0px; display: block;">
                                        <li type="none">
                                            <input type="checkbox" id="{{$singleTable->table_name}}" class="permission-group">
                                            <label ><strong>{{$singleTable->display_as}}</strong></label>
                                            <ul type="none" class="permissions">
                                                @foreach($permissions as $singlepermission)
                                                    @if($singleTable->table_name == $singlepermission->table_name)
                                                        <li>
                                                            <input type="checkbox" name="permission_id[]" class="the-permission" value="{{$singlepermission->permission_id}}" 
                                                            @foreach($permission_roles as $permission_role)
                                                            @if($permission_role->role_id == $role->role_id && $permission_role->permission_id == $singlepermission->permission_id) checked @endif
                                                            @endforeach
                                                            >
                                                            <label>{{ ucwords("$singlepermission->permission") }} {{ ucwords("$singleTable->display_as")}}</label>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-warning" value="Update"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Content -->
    </div>
</div>

<script>
        $('document').ready(function () {

            $('.permission-group').on('change', function(){
                $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            });

            $('.permission-select-all').on('click', function(){
                $('ul.permissions').find("input[type='checkbox']").prop('checked', true);
                return false;
            });

            $('.permission-deselect-all').on('click', function(){
                $('ul.permissions').find("input[type='checkbox']").prop('checked', false);
                return false;
            });

            function parentChecked(){
                $('.permission-group').each(function(){
                    var allChecked = true;
                    $(this).siblings('ul').find("input[type='checkbox']").each(function(){
                        if(!this.checked) allChecked = false;
                    });
                    $(this).prop('checked', allChecked);
                });
            }

            parentChecked();

            $('.the-permission').on('change', function(){
                parentChecked();
            });
        });
    </script>
@endsection