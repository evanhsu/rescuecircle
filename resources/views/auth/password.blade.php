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

        <form action="{{ route('process_password_link_request') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email</label>

                <div class="col-sm-6">
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control" aria-describedby="helpBlock">
                </div>
            </div>

            <div class="form-group">
                <span id="helpBlock" class="help-block col-sm-6 col-sm-offset-3">
                    After submitting your email address, you'll receive an email with a password reset link.  
                    You'll need to click the link in the email to complete the password-reset process.
                </span>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <button type="submit" class="btn btn-default">Reset</button>
                </div>
            </div>
        </div>

</div>
@endsection