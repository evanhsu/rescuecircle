@extends('../layouts.application_layout')

@section('title','Accounts - RescueCircle')


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
	    @include('auth._users_table')
	    <a href="{{ route('new_user_for_crew',$crew->id) }}">Create New User Account</a>
	</div>

</div>
@endsection