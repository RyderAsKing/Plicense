@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    @if(Auth::user()->type == 1)
    <!-- ./col -->
    <div class="col-lg-6 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ App\Models\User::all()->count() }}</h3>

                <p>Total User</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users') }}" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ App\Models\License::all()->count() }}</h3>

                <p>Total Licenses</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <a href="{{ route('admin.licenses') }}" class="small-box-footer">More info <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-12">
        <!-- small box -->
        <div class="small-box bg-success">
            <a href="{{ route('admin.licenses.create') }}" class="small-box-footer">Create Licenses <i
                    class="fas fa-plus-circle"></i></a>
        </div>
        <div class="small-box bg-success">
            <a href="{{ route('api.update') }}" class="small-box-footer">Update API Token <i
                    class="fas fa-plus-circle"></i></a>
        </div>
    </div>
    @else
    <div class="col-lg-12 col-12">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ Auth::user()->license()->count() }}</h3>

                <p>Your Licenses</p>
            </div>
            <div class="icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-12">
        @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Your Licenses</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>License Key</th>
                            <th>IP Bound</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(Auth::user()->license()->count() > 0)
                        @foreach (Auth::user()->license()->get() as $license)
                        <tr>
                            <td>{{ $license->id }}</td>
                            <td><code>{{ $license->key }}</code></td>
                            <td>@if(strlen($license->ip) > 0)<code>{{ $license->ip }}</code>@else Not bounded @endif
                            </td>
                            <td><span
                                    class="badge @if($license->status == 'Active')bg-success @else bg-danger @endif">{{
                                    $license->status }}</span></td>
                            <td>{{ $license->expires_at->diffForHumans() }}</td>
                            <td><a href="{{ route('licenses.reissue', $license->id) }}"><button type="button"
                                        class="btn btn-block bg-gradient-primary btn-sm">Reissue</button></a>
                            </td>
                        </tr>
                        @endforeach

                        @else
                        <tr>
                            <td colspan="4" align="center">You have no licenses issued</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    @endif

</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop