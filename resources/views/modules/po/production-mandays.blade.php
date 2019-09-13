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
        var clone_item = $('#tbl tbody tr:first').clone();
        
        $('.select-item').select2();
        
        $('#btnAdd').on('click', function(e){
            e.preventDefault();

            var new_item = clone_item.clone();
                new_item.appendTo('#tbl tbody');
                new_item.find('.select-item').select2();
        });

        $('#tbl').on('click', '.del-item', function(e){
            e.preventDefault();

            if( $('#tbl tbody tr').length > 1 ) {
                $(this).parents('tr').remove();
            } else {
                return false;
            }
        });

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
            <li><a href="/po/production/{{ $po->po_number }}"><i class="fas fa-check"></i> Production Report</a></li>
            <li class="active"><i class=""></i> Mandays Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12">
                <form class="form-horizontal" action="/po/production/{{ $po->po_number }}/mandays" method="POST">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-clipboard-list"></i> &nbsp;Mandays Report</h3>
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
                                <label for="poNumber" class="col-sm-2 control-label">Shift </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="shift" placeholder="Shift" name="shift" value="">
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
                                    <table class="table table-bordered table-striped" id="tbl">
                                        <thead> 
                                            <tr>
                                                <th>Employee</th>
                                                <th>Man Hour</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr> 
                                                <td width="60%">                                            
                                                    <select class="select-item form-control" name="employees[]" style="width: 100%;">
                                                        @foreach ($employees as $el)
                                                            <option value="{{ $el->id }}">{{ $el->nik .' - '. $el->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" name="mh[]" id="mh" class="form-control" min="0" max="24" value="0" required=""></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3"><button type="button" class="btn btn-success" id="btnAdd"><i class="fa fa-user-plus"></i> Add Employee</button></td>
                                            </tr>
                                        </tfoot>
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
