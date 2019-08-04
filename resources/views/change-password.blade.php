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
                <form class="" action="#" method="POST">
                    <div class="box">
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="old">Old Password</label>
                                <input type="text" class="form-control" id="old" placeholder="Old Password" name="old" required="">
                            </div>
                            <div class="form-group">
                                <label for="new">New Password</label>
                                <input type="text" class="form-control" id="new" placeholder="New Password" name="new" required="">
                            </div>
                            <div class="form-group">
                                <label for="confirm-new">Confirm New Password</label>
                                <input type="text" class="form-control" id="confirm-new" placeholder="Confirm New Password" name="confirm-new" required="">
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
