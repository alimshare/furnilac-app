@extends('layouts.app')

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
            <div class="col-xs-12">
                <form class="form-horizontal" action="/po/new" method="POST">
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid box-primary">
                                        <div class="box-header"><h3 class="box-title">Order Information</h3></div>
                                        <div class="box-body">
                                            @csrf
                                            <div class="form-group">
                                                <label for="poNumber" class="col-sm-4 control-label">PO Number <span class="text-red">*</span></label>
                                                <div class="col-sm-8"><input type="text" class="form-control" id="poNumber" placeholder="PO Number" name="poNumber" required=""></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="transactionDate" class="col-sm-4 control-label">Transaction Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="transactionDate" placeholder="Transaction Date" name="transactionDate" required="">
                                                    <small class="">Date Format : yyyy-mm-dd</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="startDateStuffing" class="col-sm-4 control-label">Stuffing Date <span class="text-red">*</span></label>
                                                <div class="col-sm-8">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <input type="text" class="form-control" id="startDateStuffing" placeholder="Start Date" name="startDate">                                           
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <input type="text" class="form-control" id="endDateStuffing" placeholder="End Date" name="endDate">                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table class="table table-bordered">
                                                <caption><h4>Order Detail</h4></caption>
                                                <thead class="bg-gray">
                                                    <tr>
                                                        <th>Item Code</th>
                                                        <th>Qty</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>a</td>
                                                        <td>a</td>
                                                        <td class="text-center">
                                                            <a href="#"><i class="fa fa-times text-red"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-center" style="padding-top: 2rem">
                                                            <button type="button" class="btn btn-sm btn-block btn-success"><i class="fa fa-plus"></i> Add New Item to Order</button>
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
