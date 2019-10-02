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
        <h1>Purchase Order <small>New</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/po">Purchase Order</a></li>
            <li class="active"> New</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <form class="form-horizontal" action="/po/edit" method="POST">
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
                                                <div class="col-sm-8"><input type="text" class="form-control" id="poNumber" placeholder="PO Number" name="poNumber" required="" value="{{ $po->po_number }}" readonly=""></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="transactionDate" class="col-sm-4 control-label">Transaction Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control" id="transactionDate" placeholder="Transaction Date" name="transactionDate" required="" value="{{ $po->transaction_date }}">
                                                    <small class="">Format : mm/dd/yyyy</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-4 control-label">Buyer <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <select id="buyerId" name="buyerId" class="form-control select-item" style="width: 100%">
                                                        @foreach($buyers as $e)
                                                            @if ($e->id == $po->buyer_id)
                                                                <option value="{{ $e->id }}" selected="">{{ $e->name }}</option>
                                                            @else
                                                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                                                            @endif
                                                        @endforeach    
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="startDateStuffing" class="col-sm-4 control-label">SW Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <input type="date" class="form-control" id="startDate" placeholder="Start Date" name="startDate" value="{{ $po->sw_begin }}">                                           
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <input type="date" class="form-control" id="endDate" placeholder="End Date" name="endDate" value="{{ $po->sw_end }}">                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="transactionDate" class="col-sm-4 control-label">Notice Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control" id="noticeDate" placeholder="Notice Date" name="noticeDate" required="" value="{{ $po->notice_date }}">
                                                    <small class="">Format : mm/dd/yyyy</small>
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-4 control-label">PIC <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <select id="employeeId" name="picId" class="form-control select-item" style="width: 100%">
                                                        @foreach($employees as $e)
                                                            @if ($e->id == $po->pic_id)
                                                                <option value="{{ $e->id }}" selected="">{{ $e->nik . ' - ' . $e->name }}</option>
                                                            @else
                                                                <option value="{{ $e->id }}">{{ $e->nik . ' - ' . $e->name }}</option>
                                                            @endif
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
                                            <div class="nav-tabs-custom">
                                                <ul class="nav nav-tabs">
                                                  <li class="active"><a href="#price" data-toggle="tab" aria-expanded="true">Price List</a></li>
                                                  <li class=""><a href="#detail" data-toggle="tab" aria-expanded="false">Detail Order</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                  <div class="tab-pane active" id="price">
                                                    <table class="table table-bordered" id="tbl_item">
                                                        <thead class="bg-gray">
                                                            <tr>
                                                                <th>Item Code</th>
                                                                <th>Description</th>
                                                                <th>Factory Style</th>
                                                                <th>Buyer Style</th>
                                                                <th>Qty Order</th>
                                                                <th>Selling Price</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($po->prices as $p)
                                                                <tr>
                                                                    <td>{{ $p->item_code }}</td>
                                                                    <td>{{ $p->item->item_name }}</td>
                                                                    <td>{{ $p->item->factory_style }}</td>
                                                                    <td>{{ $p->item->buyer_style }}</td>
                                                                    <td>
                                                                        <input type="number" name="item[{{ $p->item_code }}][qty]" value="{{ $p->qty }}" class="form-control text-right">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="item[{{ $p->item_code }}][price]" value="{{ number_format($p->selling_price, 0, ',', '.') }}" class="form-control text-right" onkeyup="formatRupiah(this, this.value, '')">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a href="javascript:void(0)" class="mark-done-item"><i class="fa fa-check text-green" title="mark as done"></i></a>
                                                                        <a href="javascript:void(0)" class="mark-done-item"><i class="fa fa-comment" title="note"></i></a>
                                                                        <!-- <a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red" title="cancel"></i></a> -->
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="sample-row" style="display: none">
                                                                <td>
                                                                    <select class="select-item form-control" name="newItem[]" style="width: 100%;" onchange="getItemInfo(this, this.value)">
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
                                                                    <input type="number" min="1" name="newQty[]" class="form-control text-right" value="1">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control text-right" id="unit_price" name="newPrice[]" value="0" min="0" onkeyup="formatRupiah(this, this.value, '')">
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red"></i></a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="6" class="text-center" style="padding-top: 2rem">
                                                                    <button type="button" class="btn btn-sm btn-success" id="btn_add_item"><i class="fa fa-plus"></i> Add New Item to Order</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                  </div>
                                                  <div class="tab-pane" id="detail">

                                                    <table class="table table-bordered" id="tbl_part">
                                                        <thead class="bg-gray">
                                                            <tr>
                                                                <th>Part Number</th>
                                                                <th>Qty per Item</th>
                                                                <th>Base Price</th>
                                                                <th>Total Qty</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($po->detail as $d)
                                                                <tr>
                                                                    <td>{{ $d->part_number }}</td>
                                                                    <td class="text-right">{{ $d->unit_qty }}</td>
                                                                    <td class="text-right">{{ number_format($d->price, 0, ',', '.') }}</td>
                                                                    <td class="text-right">{{ $d->unit_qty * $d->qty }}</td>
                                                                    <td class="text-center">
                                                                        <a href="javascript:void(0)" class="mark-done-item"><i class="fa fa-check text-green" title="mark as done"></i></a>
                                                                        <a href="javascript:void(0)" class="mark-done-item"><i class="fa fa-comment" title="note"></i></a>
                                                                        <!-- <a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red" title="cancel"></i></a> -->
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <!-- <tfoot>
                                                            <tr>
                                                                <td colspan="6" class="text-center" style="padding-top: 2rem">
                                                                    <button type="button" class="btn btn-sm btn-success" id="btn_add_part"><i class="fa fa-plus"></i> Add New Part to Order</button>
                                                                </td>
                                                            </tr>
                                                        </tfoot> -->
                                                    </table>
                                                  </div>
                                                </div>
                                            </div>
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
<script type="text/javascript">
    $(function(){
        var clone_item = $('#tbl_item tfoot tr:first').clone().show();
        var clone_part = $('#tbl_part tfoot tr:first').clone().show();
        $('.select-item').select2();

        $('#btn_add_item').on('click', function(e){
            e.preventDefault();

            var new_item = clone_item.clone();
                new_item.appendTo('#tbl_item tbody');
                new_item.find('.select-item').select2();
        });

        $('#btn_add_part').on('click', function(e){
            e.preventDefault();

            var new_item = clone_part.clone();
                new_item.appendTo('#tbl_part tbody');
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

        $('#tbl_part').on('click', '.del-item', function(e){
            e.preventDefault();

            if( $('#tbl_part tbody tr').length > 1 ) {
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

    var rupiah = document.getElementById('rupiah');

    /* Fungsi formatRupiah */
    function formatRupiah(elem, angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split           = number_string.split(','),
        sisa            = split[0].length % 3,
        rupiah          = split[0].substr(0, sisa),
        ribuan          = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        // let result = prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');

        elem.value = rupiah;
    }
</script>
@endsection