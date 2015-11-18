@extends('../layouts.application_layout')


@section('title','Crews - RescueCircle')


@section('content')
<div id="container-fluid" class="container-fluid" style="background: url('assets/images/map-dim.jpg'); background-size:cover; text-align:center;">
    <h1>Listing All Crews</h1>

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
				<tr><th>Crew Name</th>
					<th>ID</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			@foreach($crews as $crew)
				<tr>
					<td><a href="{{ route('edit_crew', array('id' => $crew->id)) }}">{{ $crew->name }}</td>
					<td>{{ $crew->id }}</td>
					<td><form action="{{ route('destroy_crew',$crew->id) }}" method="POST" class="form-inline">
							{{ csrf_field() }}
							<button type="submit" class="btn btn-sm btn-danger">X</button>
						</form></td>
				</tr>

			@endforeach
			</tbody>
		</table>
		<a href="{{ route('new_crew') }}">Create New Crew</a>
	</div>
</div>
@endsection