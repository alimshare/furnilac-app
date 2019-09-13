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
 <script type="text/javascript">
        
    function searchPo(){
        let poNumber = document.getElementById('po_number');
        document.location = '/po/production/' + (poNumber.value);
    }

    function checkByEnter(elem) {
         // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("btnCari").click();
        }
    }

 </script>
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
                        <div class="row">
                            <div class="col-md-6">                                
                                <div class="input-group">
                                    <span class="input-group-addon" for="po_number">Search Purchase Order</span>
                                    <input type="text" name="po_number" id="po_number" placeholder="Input Your Purchase Order Number ..." value="{{ $poNumber }}" class="form-control" onkeyup="checkByEnter(this)">
                                    <span class="input-group-btn"><button type="submit" id="btnCari" class="btn btn-success btn-flat" onclick="searchPo()">Search</button></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ( !isset($obj) || $obj == null)
                        @include('components.alert')
                    @else

                    <div class="box-body">
                        @include('components.alert')

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li class="active"><a href="#summary" data-toggle="tab" aria-expanded="true">Summary</a></li>
                              <li class=""><a href="#progress" data-toggle="tab" aria-expanded="false">Production Report</a></li>
                              <li class=""><a href="#mandays" data-toggle="tab" aria-expanded="false">Man Days Report</a></li>
                            </ul>
                            <div class="tab-content">
                              <div class="tab-pane active" id="summary">                              
                                <table class="table table-bordered" id="tbl-info-po">
                                    <tr>
                                        <td width="20%" class="lightgray">Purchase Order Number</td>
                                        <td>{{ $obj->po_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="lightgray">Buyer</td>
                                        <td>{{ $obj->buyer->name }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="lightgray">PIC</td>
                                        <td>{{ $obj->pic->nik }} - {{ $obj->pic->name }}</td>
                                    </tr>  
                                    <tr>
                                        <td class="lightgray">Transaction Date</td>
                                        <td>{{ date('M d, Y', strtotime($obj->transaction_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="lightgray">SW Date</td>
                                        <td>{{ date('M d, Y', strtotime($obj->sw_begin)) }} to {{ date('M d, Y', strtotime($obj->sw_end)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="lightgray">Notice Date</td>
                                        <td>{{ date('M d, Y', strtotime($obj->notice_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="lightgray">Order Detail</td>
                                        <td class="">
                                            <table class="table table-bordered table-striped">
                                                <thead>                                                    
                                                    <tr>
                                                        <th class="">Part Item</th>
                                                        <th class="">Qty per Item</th>
                                                        <th class="">Qty Order</th>
                                                        <th class="">Current Output / Total Production</th>
                                                    </tr>
                                                </thead>

                                                @foreach($detailPO as $detail)
                                                    <tr>
                                                        <td>{{ $detail->part_number }}</td>
                                                        <td>{{ $detail->unit_qty }}</td>
                                                        <td>{{ $detail->qty }}</td>
                                                        <td>
                                                            {{ $detail->output }} / {{ $detail->total_qty }} <br>
                                                            <div class="progress progress-sm">
                                                                <div class="progress-bar progress-bar-{{ $detail->color }} progress-bar-striped" role="progressbar" aria-valuenow="{{ $detail->output }}" aria-valuemin="0" aria-valuemax="{{ $detail->total_qty }}" style="width: {{ $detail->percentage.'%' }}"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </table>                                            
                                        </td>
                                    </tr>                       
                                </table>
                              </div>
                              <!-- /.tab-pane -->
                              
                              <div class="tab-pane" id="progress">
                                <div class="row">
                                    <div class="col-xs-12" style="margin-bottom: 10px">
                                        <a href="/po/production/{{ $obj->po_number }}/report" class="btn btn-flat btn-default"><i class="fas fa-clipboard-list"></i> &nbsp;Update Progress Report</a>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped">
                                    <thead>                                        
                                        <tr>
                                            <th>Production Date</th>
                                            <th>Reported By</th>
                                            <th>Part</th>
                                            <th>Qty Output</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    @foreach ($obj->productionReport as $report)
                                        <tr>
                                            <td>{{ $report->reported_date }}</td>
                                            <td>{{ $report->reporter->name }}</td>
                                            <td>{{ $report->part_number }}</td>
                                            <td>{{ $report->qty_output }}</td>
                                            <td><a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red"></i></a></td>
                                        </tr>
                                    @endforeach

                                </table>
                              </div>
                              <!-- /.tab-pane -->
                              
                              <div class="tab-pane" id="mandays">
                                
                                <div class="row">
                                    <div class="col-xs-12" style="margin-bottom: 10px">
                                        <a href="/po/production/{{ $obj->po_number }}/mandays" class="btn btn-flat btn-default"><i class="fas fa-clipboard-list"></i> &nbsp;Update Man Days Report</a>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped">
                                    <thead>                                        
                                        <tr>
                                            <th>Date</th>
                                            <th>Shift</th>
                                            <th>Reported By</th>
                                            <th>NIK</th>
                                            <th>Name</th>
                                            <th>Man Hour</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    @foreach ($obj->mandaysReport as $report)
                                        <tr>
                                            <td>{{ $report->reported_date }}</td>
                                            <td>{{ $report->shift }}</td>
                                            <td>{{ $report->reporter->name }}</td>
                                            <td>{{ $report->employee->nik }}</td>
                                            <td>{{ $report->employee->name }}</td>
                                            <td>{{ $report->man_hour }}</td>
                                            <td><a href="javascript:void(0)" class="del-item"><i class="fa fa-times text-red"></i></a></td>
                                        </tr>
                                    @endforeach
                                    
                                </table>

                              </div>
                              <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                          </div>
                    </div>
                    
                    @endif

                </div>
                
            </div>
        </div>
    </section>

@endsection
