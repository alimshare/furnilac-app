@extends('layouts.app')

@section('header-script')
<link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
<style type="text/css">
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d2d6de;
     border-radius: 0px; 
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 23px;
}
</style>
@endsection

@section('content')

    <section class="content-header">
        <h1>Production Report <small></small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li>Report</li>
            <li class="active">Production Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="" action="" method="POST">
                    <div class="box">
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label>Group</label>
                                <select id="groupId" name="groupId" class="form-control select-item" style="width: 100%">
                                    <option value="">All Group</option>
                                    @foreach($groups as $g)
                                        <option value="{{ $g->id }}">{{ $g->section . ' - ' . $g->name }}</option>
                                    @endforeach    
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Period</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="date" class="form-control" name="startDate" required="">
                                        <p class="help-block text-right">Start Date</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="date" class="form-control" name="endDate" required="">
                                        <p class="help-block text-right">End Date</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-center">
                            <button type="submit" class="btn btn-flat btn-success">Export</button>
                            <a href="#" onclick="history.back()" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($data))
            <div class="row">
                <div class="col-xs-12">
                    @if (count($data) > 0)
                    <!-- <div class="box"> -->
                        <!-- <div class="box-body"> -->
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead class="bg-green">
                                        <tr>
                                            <th>Date</th>
                                            <th>Group</th>
                                            <th>PO Number</th>
                                            <th>Part Number</th>
                                            <th>Qty Output</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $d)
                                            <tr>
                                                <td>{{ $d->reported_date }}</td>
                                                <td>{{ $d->group_name }}</td>
                                                <td>{{ $d->po_number }}</td>
                                                <td>{{ $d->part_number }}</td>
                                                <td>{{ $d->qty_output }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        <!-- </div>                     -->
                    <!-- </div> -->
                    @else                        
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <p>No data available !</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </section>

@endsection

@section('footer-script')
<script src="/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.select-item').select2();
    });
</script>
@endsection