@extends('layouts.app')

@section('header-script')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection

@section('footer-script')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $('.dt').DataTable({
            "columnDefs": [
                { "orderable": false,  "targets": 5 },
                { "searchable": false,  "targets": 5 },
            ]
        });     

        function confirmDelete(poNumber){
          var response = confirm('Yakin ingin menghapus data ini ?');
          if (response) {
            document.getElementById('deletedPONumber').value = poNumber;
            document.getElementById('formDelete').submit();
          }
        } 
    </script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Purchase Order <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fas fa-clipboard-list"></i> Purchase Order</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        @include('components.alert')

                        <div class="text-right" style="margin-bottom: 6px;">
                            <a href="/po/new" class="btn btn-primary btn-flat"> <i class="fa fa-plus"></i> New Purchase Order</a>
                            <!-- <a href="/po/export" class="btn btn-success btn-flat"> <i class="fa fa-file-excel"></i> Export</a> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered dt">
                                <thead>
                                    <tr>                        
                                        <th>Transaction Date</th>
                                        <th>PO Number</th>
                                        <th>Buyer</th>
                                        <th>PIC</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $o)
                                        <tr>
                                            <td>{{ $o->transaction_date }}</td>
                                            <td>{{ $o->po_number }}</td>
                                            <td>{{ $o->buyer->name }}</td>
                                            <td>{{ $o->pic->nik . ' - ' .$o->pic->name }}</td>
                                            <td>{{ $o->status }}</td>
                                            <td>
                                                <a href="/po/monitor/{{ base64_encode($o->po_number) }}" class="btn btn-info btn-flat"><i class="fa fa-search"></i> View</a>
                                                <a href="/po/edit/{{ base64_encode($o->po_number) }}" class="btn btn-primary btn-flat"><i class="fa fa-pencil"></i> Edit</a>
                                                <a href="#" class="btn btn-danger btn-flat" onclick="confirmDelete(`{{ $o->po_number }}`)"><i class="fa fa-times"></i> Cancel</a>
                                                <!-- <a href="/po/production/{{ $o->po_number }}" class="btn btn-default btn-flat">Production</a> -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <form action="/po/delete" method="POST" id="formDelete">
        @csrf
        <input type="hidden" name="deletedPONumber" id="deletedPONumber">
    </form>


@endsection
