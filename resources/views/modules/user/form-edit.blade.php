@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>User <small>Edit</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/user">User</li>
            <li class="active"> Edit</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="form-horizontal" action="/user/edit" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ $obj->id }}">
                            <div class="form-group">
                                <label for="email" class="col-sm-4 control-label">Email <span class="text-red">*</span></label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="email" placeholder="Email Address" name="email" required="" value="{{ $obj->email }}" readonly=""></div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-4 control-label">Name <span class="text-red">*</span></label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="name" placeholder="Full Name" name="name" required="" value="{{ $obj->name }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="role_id" class="col-sm-4 control-label">Role <span class="text-red">*</span></label>
                                <div class="col-sm-8">
                                    <select name="role_id" id="role_id" class="form-control">
                                        @foreach($obj->roles as $role)
                                            <option value="{{$role->id}}" {{ ($role->id == $obj->role_id) ? "selected='selected'" : "" }} >{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Save</button>
                            <a href="/user" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
