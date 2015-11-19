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


</div>
@endsection