@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Create License</h1>
@stop

@section('content')
<div class="row">

    <div class="col-12">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{$error}}<br>
            @endforeach
        </div>
        @endif
        <div class="card card-primary">
            <!-- /.card-header -->
            <!-- form start -->
            <form method="post" action={{ route('admin.licenses.create') }}>
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Select a user</label>
                        <select id="user_id" class="form-control js-example-basic-single" name="user_id">
                            <option value=null>None</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} | {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h3 class="text-center">Or create a user</h3>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="name" class="form-control" id="name" placeholder="Enter full name" name="name"
                            value={{ old('name') }}>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email"
                            value={{ old('email') }}>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password"
                            name="password">
                    </div>
                    <hr>

                    <div class="form-group">
                        <label for="expires_at">Expires in </label> <span class="badge badge-pill badge-primary"
                            id="days">@if(old('expires_at')) {{ old('expires_at') }}@else 1 @endif days</span>
                        <script>
                            function updateTextInput(val) {
                                document.getElementById('days').innerHTML = val + " days";
                            }
                        </script>
                        <input type="range" class="custom-range" id="expires_at" min="1" max="100" name="expires_at"
                            value=@if(old('expires_at')) {{ old('expires_at') }}@else "1" @endif
                            onchange="updateTextInput(this.value);">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
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
    $(document).ready(function() {
        $('#user_id').select2();
    });
</script>
@stop