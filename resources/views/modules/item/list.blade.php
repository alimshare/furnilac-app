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
                { "orderable": false,  "targets": 4 },
                { "searchable": false,  "targets": 4 },
            ],
        });  

        function confirmDelete(code){
          var response = confirm('Yakin ingin menghapus data ini ?');
          if (response) {
            document.getElementById('deletedCode').value = code;
            document.getElementById('formDelete').submit();
          }
        }     
    </script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Item & Parts <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-cube"></i> Item</li>
        </ol>
    </section>

    <section class="content container-fluid">      
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        @include('components.alert')
                    </div>
                    <div class="box-body">
                        <div class="text-right" style="margin-bottom: 6px;">
                            <a href="/item/new" class="btn btn-primary btn-flat"> <i class="fa fa-plus"></i> New Item</a>
                            <a href="/item/export" class="btn btn-success btn-flat"> <i class="fa fa-file-excel"></i> Export</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dt">
                                <thead>
                                    <tr>                        
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Factory Style</th>
                                        <th>Buyer Style</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $o)
                                        <tr>
                                            <td>{{ $o->item_code }}</td>
                                            <td>{{ $o->item_name }}</td>
                                            <td>{{ $o->factory_style }}</td>
                                            <td>{{ $o->buyer_style }}</td>
                                            <td class="text-left">
                                                <a href="/item/{{ $o->id }}" class="btn btn-success btn-flat">Part</a>
                                                <a href="/item/edit/{{ $o->id }}" class="btn btn-primary btn-flat">Edit</a>
                                                <a href="#" class="btn btn-danger btn-flat" onclick="confirmDelete(`{{ $o->item_code }}`)">Delete</a>
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

    <form action="/item/delete" method="POST" id="formDelete">
        @csrf
        <input type="hidden" name="deletedCode" id="deletedCode">
    </form>

@endsection
