@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Parts <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/item"><i class="fa fa-cube"></i> Item</a></li>
            <li><a href="/item/{{ $item_id }}"><i class="fa fa-cube"></i> Part</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="form-horizontal" action="/item/{{ $item_id }}/edit" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Item Code</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="nik" placeholder="Item code" name="item_code" required="" value="{{ $object->item_code }}" readonly=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Part Number</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="part_number" placeholder="Part Number" name="part_number" required="" value="{{ $object->part_number }}" readonly=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="part_name" placeholder="Name" name="part_name" required="" value="{{ $object->part_name }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="qty" class="col-sm-4 control-label">Qty</label>
                                <div class="col-sm-8"><input type="number" class="form-control" id="qty" placeholder="Qty" name="qty" required="" value="{{ $object->qty }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Price</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="price" placeholder="Price" name="price" required="" value="{{ $object->price }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="price_active_period" class="col-sm-4 control-label">Active Period</label>
                                <div class="col-sm-8"><input type="date" class="form-control" id="price_active_period" placeholder="Active Period" name="price_active_period" required="" value="{{ $object->price_active_period }}"></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-success">Update</button>
                            <a href="/item/{{ $item_id }}" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
