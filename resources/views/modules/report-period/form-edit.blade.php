@extends('layouts.app')

@section('header-script')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection

@section('footer-script')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $('.dt').DataTable({
            "columnDefs": [
            ]
        });     
    </script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Report Period <small>Edit</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/report-period"> Report Period</li>
            <li class="active"> Edit</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-md-10">
                <form class="form-horizontal" action="/group/edit" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="start_period" class="col-sm-2 control-label">Start Period</label>
                                <div class="col-sm-10"><input type="date" class="form-control" id="start_period" placeholder="Start Period" name="start_period" required=""  value="{{ $obj->start_period }}" disabled=""></div>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{ $obj->id }}">
                            <div class="form-group">
                                <label for="end_period" class="col-sm-2 control-label">End Period</label>
                                <div class="col-sm-10"><input type="date" class="form-control" id="end_period" placeholder="End Period" name="end_period" required=""  value="{{ $obj->name }}" disabled=""></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <!-- <button type="submit" class="btn btn-flat btn-primary">Edit</button> -->
                            <!-- <button type="Reset" class="btn btn-flat btn-default">Reset</button> -->
                            <a href="/report-period" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header"></div>
                        <div class="box-body">
                            <table class="dt table table-bordered table-striped" >
                                <thead>
                                    <tr>
                                        <td>Item Code</td>
                                        <td>Part Number</td>
                                        <td>Price</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prices as $o)
                                        <tr>
                                            <td>{{ $o->item['item_code'] }}</td>
                                            <td>{{ $o->part['part_number'] }}</td>
                                            <td style="text-align: right;">{{ $o->price }}</td>
                                        </tr>
                                    @endforeach                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
