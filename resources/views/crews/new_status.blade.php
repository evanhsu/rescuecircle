@extends('../layouts.application_layout')

@section('title','Update - RescueCircle')


@section('content')
<div id="container-fluid" class="container-fluid background-container">

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
        <h1>Crew Status Update</h1>
        <?php
                $freshness = $crew->freshness();
                switch($freshness) {
                    case "missing":
                        $alert = array( 'msg'   => "No updates have ever been posted for this Crew!<br />\nThis Crew won't show up on the map until a Status Update is submitted.",
                                        'type'  => 'warning');
                        break;
                    case "fresh":
                        $alert = array( 'msg'   => "This crew's status is still fresh",
                                        'type'  => 'success');
                        break;
                    case "stale":
                        $alert = array( 'msg'   => "This crew's status is out of date",
                                        'type'  => 'warning');
                        break;
                    case "expired":
                        $alert = array( 'msg'   => "This crew's status is expired!",
                                        'type'  => 'danger');
                        break;
                }
                if($freshness != "missing") {
                    $alert['msg'] .= "<br />Last update posted by ".$status->created_by_name." ".$status->created_at->diffForHumans(); // Function provided by the Carbon date/time library
                }

                echo "<div class=\"freshness_notification alert alert-".$alert['type']."\" role=\"alert\">\n"
                    .$alert['msg']
                    ."</div>\n";

            ?>


        <form action="{{ route('create_status') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="statusable_type" value="crew" />
            <input type="hidden" name="statusable_id" value="{{ $crew->id }}" />
            <input type="hidden" name="statusable_name" value="{{ $crew->name }}" />
            
            <div class="col-xs-12 col-md-6 form-inline">
                <h2>Location</h2>
            
                    <div class="form-static-control col-xs-12 col-sm-2 col-md-3">Latitude</div>
                    <div class="form-group">
                        <label for="latitude_deg" class="control-label sr-only">Degrees of Latitude</label>
                        <span class="input-group" style="width:120px;">
                            <span class="input-group-addon">N</span>
                            <input type="text" name="latitude_deg" id="latitude_deg" class="form-control" style="text-align:right;" value="{{ $status->latitude_deg }}" placeholder="41" aria-label "Latitude (whole degrees)">
                            <span class="input-group-addon">&deg;</span>
                        </span>
                        <span class="input-group" style="width:120px;">
                            <label for="latitude_min" class="control-label sr-only">Minutes of Latitude</label>
                            <input type="text" name="latitude_min" id="latitude_min" class="form-control" style="text-align:right;" value="{{ $status->latitude_min }}" placeholder="12.3456" aria-label "Latitude (decimal minutes)">
                            <span class="input-group-addon">'</span>
                        </span>
                    </div>
            <br />
                    <div class="form-static-control col-xs-12 col-sm-2 col-md-3">Longitude</div>
                    <div class="form-group">
                        <label for="longitude_deg" class="control-label sr-only">Degrees of Longitude</label>
                        <span class="input-group" style="width:120px;">
                            <span class="input-group-addon">W</span>
                            <input type="text" name="longitude_deg" id="longitude_deg" class="form-control" style="text-align:right;" value="{{ $status->longitude_deg }}" placeholder="120" aria-label "Longitude (whole degrees)">
                            <span class="input-group-addon">&deg;</span>
                        </span>
                        <span class="input-group" style="width:120px;">
                            <label for="longitude_min" class="control-label sr-only">Minutes of Longitude</label>
                            <input type="text" name="longitude_min" id="longitude_min" class="form-control" style="text-align:right;" value="{{ $status->longitude_min }}" placeholder="12.3456" aria-label "Longitude (decimal minutes)">
                            <span class="input-group-addon">'</span>
                        </span>
                    </div>
             
            </div>
            
            <div class="col-xs-12 col-md-6">
                <h2>Current Assignment</h2>
                <div class="form-group">
                    <label for="assigned_fire_name" class="col-xs-4 col-sm-2 control-label">Fire Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="assigned_fire_name" id="assigned_fire_name" class="form-control" value="{{ $status->assigned_fire_name }}" placeholder="i.e. The Example Fire">
                    </div>
                </div>
                <div class="form-group">
                    <label for="assigned_fire_number" class="col-xs-4 col-sm-2 control-label">Fire Number</label>
                    <div class="col-sm-6">
                        <input type="text" name="assigned_fire_number" id="assigned_fire_number" class="form-control" value="{{ $status->assigned_fire_number }}" placeholder="AA-BBB-123456">
                    </div>
                </div>
                <div class="form-group">
                    <label for="assigned_supervisor" class="col-xs-4 col-sm-2 control-label">Reporting To:</label>
                    <div class="col-sm-6">
                        <input type="text" name="assigned_supervisor" id="assigned_supervisor" class="form-control" value="{{ $status->assigned_supervisor }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="assigned_supervisor_phone" class="col-xs-4 col-sm-2 control-label">Phone</label>
                    <div class="col-sm-6">
                        <input type="text" name="assigned_supervisor_phone" id="assigned_supervisor_phone" class="form-control" value="{{ $status->assigned_supervisor_phone }}" placeholder="XXX-XXX-XXXX">
                    </div>
                </div>
                <div class="form-group">
                    <label for="staffing_value2" class="col-xs-4 col-sm-2 control-label">Day 1</label>
                    <div class="col-sm-6">
                        <input type="text" name="staffing_value2" id="staffing_value2" class="form-control" value="{{ $status->staffing_value2 }}" placeholder="mm/dd/yyyy">
                    </div>
                </div>
            </div>


            <div class="col-xs-12">
                <h2>Staffing</h2>
                <div class="form-group">
                    <label for="staffing_value1" class="col-xs-4 col-sm-2 control-label">Crew Size</label>
                    <div class="col-xs-4 col-sm-2 col-md-1">
                        <input type="text" name="staffing_value1" id="staffing_value1" class="form-control" value="{{ $status->staffing_value1 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="manager_name" class="col-xs-4 col-sm-2 control-label">Current CRWB</label>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <input type="text" name="manager_name" id="manager_name" class="form-control" value="{{ $status->manager_name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="manager_phone" class="col-xs-4 col-sm-2 control-label">Phone</label>
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <input type="text" name="manager_phone" id="manager_phone" class="form-control" value="{{ $status->manager_phone }}" placeholder="XXX-XXX-XXXX">
                    </div>
                </div>
                <div class="form-group">
                    <label for="comments2" class="col-xs-4 col-sm-2 control-label">Available</label>
                    <div class="checkbox col-xs-12 col-sm-4 col-md-3">
                        <label>
                            <input type="checkbox" name="comments2" id="comments2" value="true" class="" {{ $status->comments2 == 'false' ? '':'checked' }}>
                        </label>
                    </div>
                </div>
            </div>


            <div class="col-xs-12">
                <h2>Remarks</h2>
                <div class="form-group">
                    <label for="comments1" class="col-xs-4 col-sm-2 control-label">Situation</label>
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <textarea name="comments1" id="comments1" class="form-control" rows="4">{{ $status->comments1 }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-default">Update</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
    
</div>

@endsection