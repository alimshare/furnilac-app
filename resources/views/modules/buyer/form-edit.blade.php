@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Buyer <small>Edit</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/buyer"> Buyer</li>
            <li class="active"> Edit</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="form-horizontal" action="/buyer/edit" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{ $obj->id }}">
                            <div class="form-group">
                                <label for="nik" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="name" placeholder="Name" name="name" required=""  value="{{ $obj->name }}"></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Edit</button>
                            <button type="Reset" class="btn btn-flat btn-default">Reset</button>
                            <a href="/buyer" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
