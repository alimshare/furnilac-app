@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Change Password</h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"> Change Password</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="" action="/user/change-password" method="POST">
                    <div class="box">
                        <div class="box-header">
                            @include('components.alert')
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="old">Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Email" name="email" required="" value="{{ Auth::user()->email }}" disabled="">
                            </div>
                            <div class="form-group">
                                <label for="old">Old Password</label>
                                <input type="text" class="form-control" id="old_password" placeholder="Old Password" name="old_password" required="">
                            </div>
                            <div class="form-group">
                                <label for="new">New Password</label>
                                <input type="password" class="form-control" id="new_password" placeholder="New Password" name="new_password" required="">
                            </div>
                            <div class="form-group">
                                <label for="confirm-new">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" placeholder="Confirm New Password" name="confirm_password" required="">
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary"> <i class="fa fa-key"></i> Change Password</button>
                            <a href="/" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
