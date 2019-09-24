@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>Item <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/item"><i class="fa fa-cube"></i> Item</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-6">
                <form class="form-horizontal" action="/item/edit" method="POST">
                    <div class="box">
                        <div class="box-header">
                        </div>
                        <div class="box-body">
                            @csrf
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Item Code</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="item_code" placeholder="Item code" name="item_code" required="" value="{{ $object->item_code }}" readonly=""></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Item Name</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="item_name" placeholder="Item Name" name="item_name" required="" value="{{ $object->item_name }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Factory Style</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="factory_style" placeholder="Factory Style" name="factory_style" required="" value="{{ $object->factory_style }}"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="col-sm-4 control-label">Buyer Style</label>
                                <div class="col-sm-8"><input type="text" class="form-control" id="buyer_style" placeholder="Buyer Style" name="buyer_style" required="" value="{{ $object->buyer_style }}"></div>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-flat btn-success">Update</button>
                            <a href="#" onclick="history.back()" class="btn btn-flat btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
