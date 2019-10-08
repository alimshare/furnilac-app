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
                { "orderable": false,  "targets": 1 },
                { "searchable": false,  "targets": 1 },
            ]
        });     

        function confirmDelete(id){
          var response = confirm('Yakin ingin menghapus data ini ?');
          if (response) {
            document.getElementById('deletedId').value = id;
            document.getElementById('formDelete').submit();
          }
        }  
    </script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Group <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-users"></i> Group</li>
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
                            <a href="/group/new" class="btn btn-primary btn-flat"> <i class="fa fa-plus"></i> New Group</a>
                            <!-- <a href="/buyer/export" class="btn btn-success btn-flat"> <i class="fa fa-file-excel"></i> Export</a> -->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dt">
                                <thead>
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Section</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $o)
                                        <tr>
                                            <td>{{ $o->name }}</td>
                                            <td>{{ $o->section }}</td>
                                            <td>
                                                <a href="/group/edit/{{ $o->id }}" class="btn btn-primary">Edit</a>
                                                <a href="#" class="btn btn-danger" onclick="confirmDelete({{ $o->id }})">Delete</a>
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

    <form action="/group/delete" method="POST" id="formDelete">
        @csrf
        <input type="hidden" name="deletedId" id="deletedId">
    </form>


@endsection
