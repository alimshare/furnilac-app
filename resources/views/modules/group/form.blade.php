@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Group <small>New</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/group">Group</li>
            <li class="active"> New</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-md-8">
                <form class="form-horizontal" action="/group/new" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="nik" class="col-sm-2 control-label">Nama Bagian</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="section" placeholder="Nama Bagian" name="section" required=""></div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Nama Group</label>
                                <div class="col-sm-10"><input type="text" class="form-control" id="name" placeholder="Nama Group" name="name" required=""></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Save</button>
                            <a href="/group" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
