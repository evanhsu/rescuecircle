@extends('../layouts.application_layout')

@section('title','RescueCircle')


@section('content')
<div id="container-fluid" class="container-fluid background-container">
    Create New Crew<br />

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('store_crew') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="task" class="col-sm-3 control-label">Crew Name</label>

            <div class="col-sm-6">
                <input type="text" name="name" id="name" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-2">
                <button type="submit" class="btn btn-default">Create</button>
            </div>
        </div>
    </form>

    
</div>

@endsection