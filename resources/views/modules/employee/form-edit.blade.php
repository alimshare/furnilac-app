@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Employee <small>Edit</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/employee"> Employee</li>
            <li class="active"> Edit</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="form-horizontal" action="/employee/edit" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ $obj->id }}">
                            <div class="form-group">
                                <label for="nik" class="col-sm-2 control-label">NIK</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="nik" placeholder="NIK" name="nik" required="" value="{{ $obj->nik }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="name" placeholder="Name" name="name" required=""  value="{{ $obj->name }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="ktp" class="col-sm-2 control-label">KTP</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="ktp" placeholder="Nomor KTP" name="ktp" value="{{ $obj->ktp }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="rekening" class="col-sm-2 control-label">Rekening</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="rekening" placeholder="Nomor Rekening" name="rekening" required="" value="{{ $obj->rekening }}"></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Edit</button>
                            <button type="Reset" class="btn btn-flat btn-default">Reset</button>
                            <a href="/employee" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
