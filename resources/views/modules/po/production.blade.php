@extends('layouts.app')

@section('header-script')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
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
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">

    $(function(){
        $('.select-item').select2();

        $('.dt').DataTable({
            "columnDefs": [
            ]
        });
    });

    function setMax(elem, max) {
        let val = elem.value;
        if (val > max) {
            elem.value = max;
        }

        if (val < 0) {
            elem.value = 0;
        }
    }

    function selectPo(elem, poNumber){
        $.get('/api/po/'+poNumber+'/part', function(result){
            let parent = $(elem).parents('tr');
            let target = parent.find('.parts');
            target.html('');
            target.append("<option value=''>--Choose--</option>");
            result.forEach(function(item, index){
                target.append("<option value"+ item.part_number +">"+ item.part_number +"</option>");
            });
        });
    }

    function getPartInfo(elem) {
        let parent = $(elem).parents('tr');
        let partNumber = elem.value;
        let poNumber = parent.find('.po-number').val();
        
        $.get('/api/po/'+poNumber+'/part/'+partNumber, function(result){
            console.log(result);
            parent.find('.total-order-container').html("<input type='text' value='"+result.total_order+"' class='input-total-order form-control' readonly>");
            parent.find('.production-output-container').html("<input type='text' value='"+result.production_output+"' class='input-production-output form-control' readonly>");

            let max = result.total_order - result.production_output;
            if (max > 0) {
                parent.find('.current-output-container').html("<input type='number' value='0' min='0' max='"+max+"' class='input-current-output form-control' onkeyup='setMax(this, "+max+")' onchange='setMax(this, "+max+")' onblur='setMax(this, "+max+")' name='output["+poNumber+"]["+partNumber+"]'>");
            } else {
                parent.find('.current-output-container').html("");
            }

        });
    }
    
    var clone_item = $('#tbl tbody tr:first').clone();
    $('#btn_add_item').on('click', function(e){
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
</script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Production Report <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/po">Purchase Order</a></li>
            <li class="active"><i class=""></i> Progress Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">


            <div class="col-xs-12">
                <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Form</a></li>
              <li><a href="#tab_2" data-toggle="tab">History</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <form class="form-horizontal" action="/po/production/save" method="POST">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <!-- <h3 class="box-title"><i class="fas fa-clipboard-list"></i> &nbsp;Production Report</h3> -->
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="reportDate" class="col-sm-2 control-label">Report Date <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" id="reportDate" placeholder="Report Date" name="reportDate" required="">
                                    <small class="">Format : mm/dd/yyyy</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pic" class="col-sm-2 control-label">Group <span class="text-red">*</span></label>
                                <div class="col-sm-5">
                                    <select id="groupId" name="groupId" class="form-control select-item" style="width: 100%">
                                        @foreach($groups as $g)
                                            <option value="{{ $g->id }}">{{ $g->section . ' - ' . $g->name }}</option>
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
                                                <th>PO Number</th>
                                                <th>Part Number</th>
                                                <th>Total Order</th>
                                                <th>Production Output</th>
                                                <th>Qty Output</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="select-item po-number" name="poNumber[]" style="width: 100%" placeholder="" onchange="selectPo(this, this.value)">
                                                        <option value="">--Choose--</option>
                                                        @foreach($pos as $o)
                                                            <option value="{{ $o->po_number }}">{{ $o->po_number }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="select-item parts" name="partNumber[]" style="width: 100%" placeholder="Part Number" onchange="getPartInfo(this)"></select>
                                                </td>
                                                <td class="total-order-container"></td>
                                                <td class="production-output-container"></td>
                                                <td class="current-output-container"></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <button class="btn btn-flat btn-success" type="button" id="btn_add_item"><i class="fa fa-plus"></i> Add</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-primary">Save</button>
                            <a href="/po" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
              </div>
              <div class="tab-pane" id="tab_2">
                <table class="table table-bordered table-striped dt">
                    <thead>
                        <tr>
                            <th>Report Date</th>
                            <th>Group</th>
                            <th>PO Number</th>
                            <th>Part Number</th>
                            <th>Qty Output</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productions as $o)
                        <tr>
                            <td>{{ $o->reported_date }}</td>
                            <td>{{ $o->group->section }} - {{ $o->group->name }}</td>
                            <td>{{ $o->po_number }} </td>
                            <td>{{ $o->part_number }} </td>
                            <td class="text-right">{{ $o->qty_output }}</td>
                            <td class="text-center">
                                <a href="javascript:void(0)"><i class="fa fa-times text-red" title="cancel"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>                
              </div>
            </div>

            </div>
        </div>
    </section>

@endsection
