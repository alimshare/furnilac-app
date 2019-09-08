@extends('layouts.app')

@section('header-script')
<style type="text/css">
     .dark {
        background-color: #222d32;
        color : white;
    }
    .lightgray {
        background-color: #f9f9f9;
    }
</style>
@endsection

@section('footer-script')
@endsection

@section('content')

    <section class="content-header">
        <h1>Production Report <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fas fa-check"></i> Production Report</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12">
                
                <div class="box">
                    
                    <div class="box-header with-border">
                        @include('components.alert')
                        <!-- <div class="text-right" style="margin-bottom: 6px;">
                            <a href="/po/new" class="btn btn-primary btn-flat"> <i class="fa fa-plus"></i> New Purchase Order</a>
                        </div> -->
                        <!-- <h3>search Purchase Order </h3> -->
                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="input-group">
                                    <span class="input-group-addon" for="po_number">Search Purchase Order</span>
                                    <input type="text" name="po_number" placeholder="Input Your Purchase Order Number ..." class="form-control">
                                    <span class="input-group-btn"><button type="submit" class="btn btn-success btn-flat">Search</button></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li class=""><a href="#summary" data-toggle="tab" aria-expanded="true">Summary</a></li>
                              <li class="active"><a href="#progress" data-toggle="tab" aria-expanded="false">Production Report</a></li>
                              <li class=""><a href="#mandays" data-toggle="tab" aria-expanded="false">Man Days Report</a></li>
                            </ul>
                            <div class="tab-content">
                              <div class="tab-pane" id="summary">                              
                                <table class="table table-bordered" id="tbl-info-po">
                                    <tr>
                                        <td width="20%" class="lightgray">Purchase Order Number</td>
                                        <td>PO-00001</td>
                                    </tr>
                                    <tr>
                                        <td class="lightgray">Buyer</td>
                                        <td>IKEA Alam Sutera</td>
                                    </tr>  
                                    <tr>
                                        <td class="lightgray">Transaction Date</td>
                                        <td>Jun 08, 2019</td>
                                    </tr> 
                                    <tr>
                                        <td class="lightgray">Status</td>
                                        <td>PRODUCTION</td>
                                    </tr>  
                                    <tr>
                                        <td class="lightgray">Order Detail</td>
                                        <td class="">
                                            <table class="table table-bordered table-striped">
                                                <thead>                                                    
                                                    <tr>
                                                        <th class="">Part Item</th>
                                                        <th class="">Qty per Item</th>
                                                        <th class="">Qty</th>
                                                        <th class="">Production</th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <td>AAF 3005</td>
                                                    <td>3</td>
                                                    <td>1000</td>
                                                    <td>3000</td>
                                                </tr>
                                                <tr>
                                                    <td>AAF 3005</td>
                                                    <td>3</td>
                                                    <td>1000</td>
                                                    <td>3000</td>
                                                </tr>
                                                <tr>
                                                    <td>AAF 3005</td>
                                                    <td>3</td>
                                                    <td>1000</td>
                                                    <td>3000</td>
                                                </tr>
                                                <tr>
                                                    <td>AAF 3005</td>
                                                    <td>3</td>
                                                    <td>1000</td>
                                                    <td>3000</td>
                                                </tr>
                                            </table>                                            
                                        </td>
                                    </tr>                       
                                </table>
                              </div>
                              <!-- /.tab-pane -->
                              
                              <div class="tab-pane active" id="progress">
                                <table class="table table-bordered table-striped">
                                    <thead>                                        
                                        <tr>
                                            <th>Production Date</th>
                                            <th>Reported By</th>
                                            <th>Item</th>
                                            <th>Part</th>
                                            <th>Qty Output</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>2019/09/08</td>
                                        <td>Jojo Sumarno</td>
                                        <td>AEF</td>
                                        <td>AEF 1000</td>
                                        <td>300</td>
                                    </tr>
                                    <tr>
                                        <td>2019/09/09</td>
                                        <td>Jojo Sumarno</td>
                                        <td>AEF</td>
                                        <td>AEF 5000</td>
                                        <td>450</td>
                                    </tr>
                                    <tr>
                                        <td>2019/09/10</td>
                                        <td>Jojo Sumarno</td>
                                        <td>AEF</td>
                                        <td>AEF 4000</td>
                                        <td>400</td>
                                    </tr>
                                    <tr>
                                        <td>2019/09/11</td>
                                        <td>Jojo Sumarno</td>
                                        <td>AEF</td>
                                        <td>AEF 2000</td>
                                        <td>100</td>
                                    </tr>
                                    <tr>
                                        <td>2019/09/12</td>
                                        <td>Jojo Sumarno</td>
                                        <td>AEF</td>
                                        <td>AEF 3000</td>
                                        <td>100</td>
                                    </tr>
                                </table>
                              </div>
                              <!-- /.tab-pane -->
                              
                              <div class="tab-pane" id="mandays">
                                
                                <table class="table table-bordered table-striped">
                                    <thead>                                        
                                        <tr>
                                            <th>Date</th>
                                            <th>Shift</th>
                                            <th>Reported By</th>
                                            <th>NIK</th>
                                            <th>Name</th>
                                            <th>Man Hour</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>2019/09/08</td>
                                        <td>1</td>
                                        <td>Azmi</td>
                                        <td>198001</td>
                                        <td>Jojo Sumarno</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>2019/09/08</td>
                                        <td>1</td>
                                        <td>Azmi</td>
                                        <td>198001</td>
                                        <td>Jojo Sumarno</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>2019/09/08</td>
                                        <td>1</td>
                                        <td>Azmi</td>
                                        <td>198001</td>
                                        <td>Jojo Sumarno</td>
                                        <td>8</td>
                                    </tr>
                                </table>

                              </div>
                              <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                          </div>
                    </div>
                        
                </div>
                
            </div>
        </div>
    </section>

@endsection
