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
        <h1>Summary Salary Report <small></small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li>Report</li>
            <li class="active">Summary Salary Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="" action="" method="POST">
                    <div class="box">
                        <div class="box-body">
                            @csrf
                            <!-- <div class="form-group">
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
                            </div> -->
                            <div class="form-group">
                                <label>Period</label>
                                <select id="periodId" name="periodId" class="form-control select-item" style="width: 100%">
                                    @foreach($periods as $p)
                                        <option value="{{ $p->id }}">{{ date('d M Y', strtotime($p->start_period)) . '  -  ' . date('d M Y', strtotime($p->end_period)) }}</option>
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                        <div class="box-footer text-center">
                            <button type="submit" name="excel" value="1" class="btn btn-flat btn-success"><i class="fa fa-file-excel"> </i> Excel</button>
                            <button type="submit" name="pdf" value="1" class="btn btn-flat btn-danger"><i class="fa fa-file-pdf"> </i> PDF</button>
                            <a href="#" onclick="history.back()" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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