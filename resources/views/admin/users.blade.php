@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Users</h1>
@stop

@section('content')
<div class="row">

    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <div id="users_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table class="table table-striped" id="users">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Licenses issued</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->license->count() }}</td>
                                <td><button type="button"
                                        class="btn btn-block bg-gradient-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    $(function () {
    $("#users").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    });
    });
</script>
@stop