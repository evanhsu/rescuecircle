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
        <div class="page-header">Status Update - Helicopter {{ $helicopter->tailnumber }}</div>
        <form action="{{ route('create_status') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name="statusable_type" value="helicopter" />
            <input type="hidden" name="statusable_id" value="{{ $helicopter->tailnumber }}" />
            
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
            </div>


            <div class="col-xs-12">
                <h2>Staffing</h2>
                <div class="form-group">
                    <label for="staffing_value_1" class="col-xs-4 col-sm-2 control-label">Short-Haulers</label>
                    <div class="col-xs-4 col-sm-2 col-md-1">
                        <input type="text" name="staffing_value_1" id="staffing_value_1" class="form-control" value="{{ $status->staffing_value_1 }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="staffing_value_2" class="col-xs-4 col-sm-2 control-label">EMTs</label>
                    <div class="col-xs-4 col-sm-2 col-md-1">
                        <input type="text" name="staffing_value_2" id="staffing_value_2" class="form-control" value="{{ $status->staffing_value_2 }}">
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