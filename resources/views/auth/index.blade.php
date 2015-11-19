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
		<table class="table">
			<thead>
				<tr><th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Crew</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			@foreach($users as $user)
				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->firstname }} {{ $user->lastname }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ empty($user->crew_id) ? "" : substr($user->crew->name,0,25) }}</td>
					<td><form action="{{ route('destroy_user',$user->id) }}" method="POST" class="form-inline">
							{{ csrf_field() }}
							<button type="submit" class="btn btn-sm btn-danger">X</button>
						</form></td>
				</tr>

			@endforeach
			</tbody>
		</table>
		<a href="{{ route('new_user_for_crew',2) }}">Create New User Account</a>
	</div>

</div>
@endsection