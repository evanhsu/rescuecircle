@extends('../layouts.application_layout')

@section('title','Reset Password')


@section('content')
<div class="container-fluid background-container">
    
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container form-box">
        <h1>Reset Password</h1>

        <form action="{{ route('reset_password') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="token" id="token" value="{{ $token }}" class="form-control">

            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email</label>

                <div class="col-sm-6">
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">New Password</label>

                <div class="col-sm-6">
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">New Password (again)</label>

                <div class="col-sm-6">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <button type="submit" class="btn btn-default">Set Password</button>
                </div>
            </div>
        </div>

</div>
@endsection