@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>User <small>New</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/user">User</li>
            <li class="active"> New</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="form-horizontal" action="/user/new" method="POST">
                    <div class="box">
                        <div class="box-header">
                            @include('components.alert')
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Email <span class="text-red">*</span></label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="email" placeholder="Email Address" name="email" required=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Name <span class="text-red">*</span></label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="name" placeholder="Full Name" name="name" required=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Password <span class="text-red">*</span></label>
                                <div class="col-sm-8"><input type="password" class="form-control" id="password" placeholder="Password" name="password" required=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Confirm Password <span class="text-red">*</span></label>
                                <div class="col-sm-8"><input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" name="confirm_password" required=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Role <span class="text-red">*</span></label>
                                <div class="col-sm-8">
                                    <select name="role_id" id="role_id" class="form-control">
                                        @foreach($data->roles as $role)
                                            <option value="{{$role->id}}">{{ $role->name }}</option>
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
