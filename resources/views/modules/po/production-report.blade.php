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

@section('footer-script')
<script src="/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.select-item').select2();
    });

    function setMax(elem, max) {
        let val = elem.value;
        if (val > max) {
            elem.value = max;
        }
    }
</script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Production Report <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/po/production"><i class="fas fa-check"></i> Production Report</a></li>
            <li class="active"><i class=""></i> Progress Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12">
                <form class="form-horizontal" action="/po/report" method="POST">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-clipboard-list"></i> &nbsp;Progress Report</h3>
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="poNumber" class="col-sm-2 control-label">PO Number </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="poNumber" placeholder="PO Number" name="poNumber" required="" readonly="" value="{{ $po->po_number }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reportDate" class="col-sm-2 control-label">Report Date <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" id="reportDate" placeholder="Report Date" name="reportDate" required="">
                                    <small class="">Format : mm/dd/yyyy</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic" class="col-sm-2 control-label">Reporter <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    <select id="employeeId" name="picId" class="form-control select-item" style="width: 100%">
                                        @foreach($employees as $e)
                                            <option value="{{ $e->id }}">{{ $e->nik . ' - ' . $e->name }}</option>
                                        @endforeach    
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic" class="col-sm-2 control-label">Output </label>
                                <div class="col-sm-10">
                                    <table class="table table-bordered table-striped">
                                        <thead> 
                                            <tr>
                                                <th>Part Number</th>
                                                <th>Total Order</th>
                                                <th>Production Output</th>
                                                <th>Current Output</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($po->detail as $detail)
                                                <tr> 
                                                    <td>{{ $detail->part_number }}</td>
                                                    <td>{{ $totalOrder  = $detail->unit_qty * $detail->qty }}</td>
                                                    <td>{{ $currentProduction = $detail->getProductionOutput() }}</td>
                                                    <td><input type="number" name="output[]" min="0" max="{{ $max = $totalOrder - $currentProduction }}" onkeyup="setMax(this, {{ $max }})" value="0" class="form-control"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Save</button>
                            <a href="/po/production/{{ $po->po_number }}" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>

@endsection
