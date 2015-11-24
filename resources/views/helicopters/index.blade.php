@extends('../layouts.application_layout')


@section('title','Helicopters - RescueCircle')


@section('content')
<div id="container-fluid" class="container-fluid background-container">
    <h1>Listing All Helicopters</h1>

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
					<th>Tailnumber</th>
					<th>Make/Model</th>
					<th>Crew</th>
					<th style="width:30px;">Update</th>
					<th style="width:30px;">Delete</th>
				</tr>
			</thead>
			<tbody>
			@foreach($helicopters as $h)
				<tr>
					<td>{{ $h->id }}</td>
					<td>{{ $h->tailnumber }}</td>
					<td>{{ $h->model }}</td>
					<td>
						@if(!empty($h->crew_id))
							<a href="{{ route('edit_crew', array('id' => $h->crew_id)) }}">{{ $h->crew->name }}</a>
						@endif
					</td>
					<td><a href="{{ route('new_status_for_helicopter',$h->tailnumber) }}" class="btn btn-primary" role="button">!</td>
					<td><form action="{{ route('destroy_helicopter',$h->id) }}" method="POST" class="form-inline">
							{{ csrf_field() }}
							<button type="submit" class="btn btn-sm btn-danger">X</button>
						</form></td>
				</tr>

			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection