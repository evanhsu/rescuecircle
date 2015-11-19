@extends('../layouts.application_layout')

@section('title','Accounts - RescueCircle')


@section('content')
<div class="container-fluid background-container">
    Create a New User Account
    
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('store_user') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="task" class="col-sm-3 control-label">Email</label>

            <div class="col-sm-6">
                <input type="text" name="email" id="email" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="task" class="col-sm-3 control-label">First Name</label>

            <div class="col-sm-6">
                <input type="text" name="firstname" id="firstname" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="task" class="col-sm-3 control-label">Last Name</label>

            <div class="col-sm-6">
                <input type="text" name="lastname" id="lastname" class="form-control">
            </div>
        </div>

		<div class="form-group">
            <label for="task" class="col-sm-3 control-label sr-only">Crew ID</label>
            <input type="hidden" name="crew_id" id="crew_id" class="form-control">
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <button type="submit" class="btn btn-default">Create</button>
            </div>
        </div>

</div>
@endsection