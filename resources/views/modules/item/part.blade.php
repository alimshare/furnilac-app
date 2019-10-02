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
            ],
        });  

        function confirmDelete(itemId, partNumber){
          var response = confirm('Yakin ingin menghapus data ini ?');
          if (response) {
            document.getElementById('deletedId').value = partNumber;
            document.getElementById('item_id').value = itemId;
            document.getElementById('formDelete').action = "/item/"+itemId+"/delete";
            document.getElementById('formDelete').submit();
            // alert(document.getElementById('formDelete').action);
          }
        }     
    </script>
@endsection

@section('content')

    <section class="content-header">
        <h1>Parts <small>Management</small></h1>
        <ol class="breadcrumb">
            <li><a href="/#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="/item"><i class="fa fa-cube"></i> Item</a></li>
            <li class="active"><i class="fa fa-cubes"></i> Part</li>
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
                            <a href="/item/{{ $list->item_id }}/new" class="btn btn-primary btn-flat"> <i class="fa fa-plus"></i> New Part</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered dt">
                                <thead>
                                    <tr>                        
                                        <th>Code</th>
                                        <th>Part Number</th>
                                        <th>Part Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $o)
                                        <tr>
                                            <td>{{ $o->item_code }}</td>
                                            <td>{{ $o->part_number }}</td>
                                            <td>{{ $o->part_name }}</td>
                                            <td>{{ $o->qty }}</td>
                                            <td class="text-right">{{ number_format($o->price,0,',','. ') }}</td>
                                            <td class="text-left">
                                                <a href="/item/{{ $list->item_id }}/edit/{{ $o->id }}" class="btn btn-primary btn-flat">Edit</a>
                                                <a href="#" class="btn btn-danger btn-flat" onclick="confirmDelete(`{{ $list->item_id }}`,`{{ $o->part_number }}`)">Delete</a>
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

    <form method="POST" id="formDelete">
        @csrf
        <input type="hidden" name="deletedId" id="deletedId">
        <input type="hidden" name="item_id" id="item_id">
    </form>

@endsection
