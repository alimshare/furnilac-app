@extends('layouts.app')

@section('header-script')
<link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
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
        <h1>Purchase Order <small>New</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="/po">Purchase Order</li>
            <li class="active"> New</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <form id="form" class="form-horizontal" action="/po/new" method="POST" onsubmit="return validateForm()">
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-8">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header"><h3 class="box-title">Order Information</h3></div>
                                        <div class="box-body">
                                            @csrf
                                            <div class="form-group">
                                                <label for="poNumber" class="col-sm-4 control-label">PO Number <span class="text-red">*</span></label>
                                                <div class="col-sm-8"><input type="text" class="form-control" id="poNumber" placeholder="PO Number" name="poNumber" ></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="transactionDate" class="col-sm-4 control-label">Transaction Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control datepicker" id="transactionDate" placeholder="Transaction Date" name="transactionDate" autocomplete="off">
                                                    <small class="">Format : dd/mm/yyyy</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-4 control-label">Buyer <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <select id="buyerId" name="buyerId" class="form-control select-item" style="width: 100%">
                                                        <option value="">--Choose--</option>
                                                        @foreach($buyers as $e)
                                                            <option value="{{ $e->id }}">{{ $e->name }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="startDateStuffing" class="col-sm-4 control-label">SW Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <input type="text" class="form-control datepicker" id="startDate" placeholder="Start Date" name="startDate" autocomplete="off">                                           
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <input type="text" class="form-control datepicker" id="endDate" placeholder="End Date" name="endDate" autocomplete="off">                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="transactionDate" class="col-sm-4 control-label">Notice Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control datepicker" id="noticeDate" placeholder="Notice Date" name="noticeDate" autocomplete="off">
                                                    <small class="">Format : dd/mm/yyyy</small>
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-4 control-label">PIC <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <select id="employeeId" name="picId" class="form-control select-item" style="width: 100%">                     
                                                        <option value="">--Choose--</option>
                                                        @foreach($employees as $e)
                                                            <option value="{{ $e->id }}">{{ $e->nik . ' - ' . $e->name }}</option>
                                                        @endforeach    
                                                    </select>
                                                </div>
                                            </div>                                          
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table class="table table-bordered" id="tbl_item">
                                                <caption><h4>Order Detail</h4></caption>
                                                <thead class="bg-gray">
                                                    <tr>
                                                        <th>Item Code</th>
                                                        <th>Factory Style</th>
                                                        <th>Buyer Style</th>
                                                        <th>Description</th>
                                                        <th>Unit Price</th>
                                                        <th>Qty</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="select-item form-control" name="itemCode[]" style="width: 100%;" onchange="getItemInfo(this, this.value)">
                                                                <option value="">--Choose--</option>
                                                                @foreach ($item as $el)
                                                                    <option value="{{ $el->item_code }}">{{ $el->item_code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control factory_style" readonly="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control buyer_style" readonly="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control description" readonly="">
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" id="unit_price" name="price[]">
                                                        </td>
                                                        <td>
                                                            <input type="number" min="1" name="qty[]" id="qty" class="form-control" value="1">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6" class="text-center" style="padding-top: 2rem">
                                                            <button type="button" class="btn btn-sm btn-block btn-success" id="btn_add_item"><i class="fa fa-plus"></i> Add New Item to Order</button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer text-center">
                            <button type="submit" class="btn btn-flat btn-primary">Save</button>
                            <a href="/po" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('footer-script')
<script src="/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript" src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    $(function(){
        var clone_item = $('#tbl_item tbody tr:first').clone();
        $('.select-item').select2();

        $('#btn_add_item').on('click', function(e){
            e.preventDefault();

            var new_item = clone_item.clone();
                new_item.appendTo('#tbl_item tbody');
                new_item.find('.select-item').select2();
        });

        $('#tbl_item').on('click', '.del-item', function(e){
            e.preventDefault();

            if( $('#tbl_item tbody tr').length > 1 ) {
                $(this).parents('tr').remove();
            } else {
                return false;
            }
        });
    })

    function getItemInfo (elem, itemCode) {
        let e = $(elem);
        $.get('/api/item/'+itemCode, function(res){
            let eParent = e.parents('tr');
            eParent.find('.factory_style').val(res.factory_style);
            eParent.find('.buyer_style').val(res.buyer_style);
            eParent.find('.description').val(res.item_name);
        });
    }

    function validateForm(){
        alert('hello');
        let form = document.forms["form"];

        if (form["poNumber"].value == "")       { alert("PO Number tidak boleh kosong"); return false; }
        if (form["buyerId"].value == "")        { alert("Buyer tidak boleh kosong"); return false; }
        if (form["startDate"].value == "")      { alert("SW Start Date tidak boleh kosong"); return false; }
        if (form["endDate"].value == "")        { alert("SW End Date tidak boleh kosong"); return false; }
        if (form["noticeDate"].value == "")     { alert("Notice Date tidak boleh kosong"); return false; }
        if (form["picId"].value == "")          { alert("PIC tidak boleh kosong"); return false; }
        if (form["transactionDate"].value == ""){ alert("Transaction Date tidak boleh kosong"); return false; }
        let units = form.unit_price;
        for (let i = 0; i < units.length; i++)  {
            if (units[i].value == "") {
                alert("Unit Price tidak boleh kosong"); return false;
            }
        }

        let qtys = form.qty;
        for (let i = 0; i < qtys.length; i++)  {
            if (qtys[i].value == "") {
                alert("Qty Item tidak boleh kosong"); return false;
            }
        }

        return true;
    }

    $(".datepicker").datepicker({
        format: 'dd/mm/yyyy'
    });

</script>
@endsection