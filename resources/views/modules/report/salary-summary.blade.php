@extends('layouts.app')

@section('header-script')
@endsection

@section('content')

    <section class="content-header">
        <h1>Salary Report <small>Summary</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/report/salary"> Dashboard</a></li>
            <li class="active">Salary Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12">
                
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                    @foreach($dateList as $date)
                                        <th>Name</th>
                                        <th class="text-center">{{ $date }}</th>
                                    @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $name => $o)
                                    <tr>                                        
                                        <td>{{ $name }}</td>
                                        @foreach($dateList as $tgl)
                                            <td class="text-right">{{ $data[$name][$tgl] }}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer text-center">
                        <button type="submit" class="btn btn-flat btn-danger"><i class="fa fa-file-pdf-o"></i> &nbsp; PDF</button>
                        <button type="submit" class="btn btn-flat btn-success"><i class="fa fa-file-excel-o"></i> &nbsp; Excel</button>
                        <a href="#" onclick="history.back()" class="btn btn-flat btn-default">Cancel</a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

@endsection

@section('footer-script')
@endsection