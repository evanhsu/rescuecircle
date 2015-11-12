@extends('../layouts.application_layout')


@section('title','RescueCircle')


@section('content')
<div id="container-fluid" class="container-fluid" style="background: url('assets/images/map-dim.jpg'); background-size:cover;">
    Create New Crew<br />

    <form action="/crew/store" method="POST" class="form-horizontal">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="task" class="col-sm-3 control-label">Crew Name</label>

            <div class="col-sm-6">
                <input type="text" name="name" id="crew-name" class="form-control">
            </div>
        </div>
    </form>

    
</div>

@endsection