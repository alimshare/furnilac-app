@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Report Period <small>New</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/report-period">Report Period</li>
            <li class="active"> New</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-md-8">
                <form class="form-horizontal" action="/report-period/new" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="start_date" class="col-sm-2 control-label">Report Period </label>
                                <div class="col-sm-10"><input type="date" class="form-control" id="start_date" placeholder="Start Period" name="start_period" required=""></div>
                            </div>
                            <div class="form-group">
                                <label for="end_date" class="col-sm-2 control-label">End Period</label>
                                <div class="col-sm-10"><input type="date" class="form-control" id="name" placeholder="End Period" name="end_period" required=""></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Save</button>
                            <a href="/report-period" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
