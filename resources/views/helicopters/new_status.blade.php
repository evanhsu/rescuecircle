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
        <h1>Helicopter Status Update</h1>

        <nav>
            <ul class="nav nav-tabs" role="tablist">
                @foreach($helicopters as $h)
                <li role="presentation"{!! ($h->id == $helicopter->id) ? " class=\"active\"" : "" !!}>
                    <a href="{{ route('new_status_for_helicopter',$h->tailnumber) }}">{{ $h->tailnumber }}</a>
                </li>
                @endforeach
            </ul>
        </nav>
        <form action="{{ route('create_status') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="statusable_type" value="helicopter" />
            <input type="hidden" name="statusable_id" value="{{ $helicopter->id }}" />
            <input type="hidden" name="statusable_name" value="{{ $helicopter->tailnumber }}" />
            
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
            <br />
                    <div class="form-group">
                        <div class="col-offset-sm-2 col-offset-md-3">
                            <a href="#" id="geolocate_button">Use current location</a>
                        </div>
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
            </div>


            <div class="col-xs-12">
                <h2>Staffing</h2>
                <div class="form-group">
                    <label for="staffing_value1" class="col-xs-4 col-sm-2 control-label">Short-Haulers</label>
                    <div class="col-xs-4 col-sm-2 col-md-1">
                        <input type="text" name="staffing_value1" id="staffing_value1" class="form-control" value="{{ $status->staffing_value1 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="staffing_value2" class="col-xs-4 col-sm-2 control-label">EMTs</label>
                    <div class="col-xs-4 col-sm-2 col-md-1">
                        <input type="text" name="staffing_value2" id="staffing_value2" class="form-control" value="{{ $status->staffing_value2 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="manager_name" class="col-xs-4 col-sm-2 control-label">Manager</label>
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
            </div>


            <div class="col-xs-12">
                <h2>Remarks</h2>
                <div class="form-group">
                    <label for="comments1" class="col-xs-4 col-sm-2 control-label">Situation</label>
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <textarea name="comments1" id="comments1" class="form-control" rows="4">{{ $status->comments1 }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comments2" class="col-xs-4 col-sm-2 control-label">Upcoming</label>
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <textarea name="comments2" id="comments2" class="form-control" rows="4">{{ $status->comments2 }}</textarea>
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


@section('scripts-postload')
@parent

<script>
    (function() {
        // Add geolocation trigger to the 'geolocate_button'
        $('#geolocate_button').click(function() {
            navigator.geolocation.getCurrentPosition(function(position) {
                // Location successfully found
                populatePositionFields(position.coords.latitude, position.coords.longitude);

            }); // End .getCurrentPosition()
        }); // End .click()
    })();

    function populatePositionFields(latitude,longitude) {
        // Coordinate input format is Decimal-degrees with "Easting" longitudes (locations in the western hemisphere have negative longitude).
        // i.e. latitude = 42.389043    longitude = -120.87849
        // Convert this into "westing" decimal-minutes and populate the form with the resulting values (western hemisphere has positive longitude).

        var lat_deg = Math.floor(Math.abs(latitude));   // Use absolute value so that floor() works as desired for negative numbers
        var lat_min = (Math.abs(latitude) - lat_deg) * 60.0;
        lat_deg = latitude >= 0 ? lat_deg : (lat_deg * 1.0); // Restore the original sign

        var lon_deg = Math.floor(Math.abs(longitude)); // Use absolute value so that floor() works as desired for negative numbers
        var lon_min = (Math.abs(longitude) - lon_deg) * 60.0;
        lon_deg = longitude < 0 ? lon_deg : (lon_deg * -1.0); // Switch from 'East' reference to 'West' reference (invert sign)

        $('#latitude_deg').val( lat_deg );
        $('#latitude_min').val( toPrecision(lat_min,6) );

        $('#longitude_deg').val( lon_deg );
        $('#longitude_min').val( toPrecision(lon_min,6) );

    } // End populatePositionFields()

</script>

@endsection