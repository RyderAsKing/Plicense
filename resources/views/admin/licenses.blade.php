@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Licenses</h1>
@stop

@section('content')
<div class="row">

    <div class="col-12">
        @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
        @endif
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <div id="licenses_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <table class="table table-striped" id="licenses">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Email</th>
                                <th>License Key</th>
                                <th>IP Bound</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($licenses as $license)
                            <tr>
                                <td>{{ $license->id }}</td>
                                <th>{{ $license->user->name }}</th>
                                <td><code>{{ $license->key }}</code></td>
                                <td>@if(strlen($license->ip) > 0)<code>{{ $license->ip }}</code>@else Not bounded @endif
                                </td>
                                <td><span
                                        class="badge @if($license->status == 'Active')bg-success @else bg-danger @endif">{{
                                        $license->status }}</span></td>
                                <td>{{ $license->expires_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.licenses.reissue', $license->id) }}">
                                        <button type="button"
                                            class="btn btn-block bg-gradient-primary btn-sm">Reissue</button>
                                    </a>
                                    <a href="{{ route('admin.licenses.expire', $license->id) }}" class="p-sm-1">
                                        <button type="button"
                                            class="btn btn-block bg-gradient-danger btn-sm">Expire</button>
                                    </a>

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
    $("#licenses").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
    });
    });
</script>
@stop