@extends('../layouts.application_layout')


@section('title','Crew Info - RescueCircle')


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
        <h2>Crew Identity</h2>
        <form action="{{ route('update_crew',$crew->id) }}" id="edit_crew_form" name="edit_crew_form" method="POST" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name" class="col-xs-12 col-sm-2 control-label">Crew Name</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="text" id="name" name="crew[name]" value="{{ $crew->name }}" class="form-control" />
                </div>
            </div>

        
            <h3>Home Base</h3>

            <div class="form-group">
                <label for="street1" class="col-xs-12 col-sm-2 control-label">Street 1</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="text" id="street1" name="crew[address_street1]" value="{{ $crew->address_street1 }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="street2" class="col-xs-12 col-sm-2 control-label">Street 2</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="text" id="street2" name="crew[address_street2]" value="{{ $crew->address_street2 }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="city" class="col-xs-12 col-sm-2 control-label">City</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="text" id="city" name="crew[address_city]" value="{{ $crew->address_city }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="state" class="col-xs-12 col-sm-2 control-label">State</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="text" id="state" name="crew[address_state]" value="{{ $crew->address_state }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="zip" class="col-xs-12 col-sm-2 control-label">Zip</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="number" id="zip" name="crew[address_zip]" value="{{ $crew->address_zip }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-xs-12 col-sm-2 control-label">Phone</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="tel" id="phone" name="crew[phone]" value="{{ $crew->phone }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="fax" class="col-xs-12 col-sm-2 control-label">Fax</label>

                <div class="col-xs-12 col-sm-10">
                    <input type="tel" id="fax" name="crew[fax]" value="{{ $crew->fax }}" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label for="logo" class="col-xs-12 col-sm-2 control-label">Logo</label>

                <div class="col-xs-8 col-sm-6 col-md-4">
                    <img src="{{ $crew->logo_filename }}?={{ $crew->updated_at }}" style="width:100px; height:100px;" />
                    <input type="file" id="logo" name="logo" class="form-control" />
                </div>
            </div>


            
            <h3>Helicopters</h3>
            <div class="form-group">
                <label for="add-helicopter-button" class="control-label sr-only">Add a Helicopter</label>
                <button class="btn btn-default" id="add-helicopter-button">Add</button>
            </div>
            <?php $i = 0; ?>
            @foreach($crew->helicopters as $helicopter)
            <div class="crew-helicopter-form" data-helicopter-id="489239">
                <div class="form-group">
                    <label for="helicopter-tailnumber" class="col-xs-12 col-sm-2 control-label">Tailnumber</label>
                    <div class="form-inline col-xs-8 col-sm-6">
                        <span><input type="text" class="form-control helicopter-tailnumber" name="crew[helicopters][{{ $i }}][tailnumber]" value="{{ $helicopter->tailnumber }}"  /></span>
                        <span><button class="btn btn-default" class="remove-helicopter-button">Release</button></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="helicopter-model" class="col-xs-12 col-sm-2 control-label">Make/Model</label>
                    <div class="form-inline col-xs-8 col-sm-6">
                        <input type="text" class="form-control helicopter-model" name="crew[helicopters][{{ $i }}][model]" value="{{ $helicopter->model }}" />
                    </div>
                </div>
            </div>
            <?php $i++; ?>
            @endforeach

            <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-default">Save</button>
                </div>
            </div>
        </form>


        <div class="crew-helicopter-form" data-helicopter-id="" id="dynamic-form-template">
            <div class="form-group">
                <label for="helicopter-tailnumber" class="col-xs-12 col-sm-2 control-label">Tailnumber</label>
                <div class="form-inline col-xs-8 col-sm-6">
                    <span><input type="text" class="form-control helicopter-tailnumber" value="N205RH" disabled /></span>
                    <span><button class="btn btn-default" class="remove-helicopter-button">Release</button></span>
                </div>
            </div>

            <div class="form-group">
                <label for="helicopter-model" class="col-xs-12 col-sm-2 control-label">Make/Model</label>
                <div class="form-inline col-xs-8 col-sm-6">
                    <input type="text" class="form-control helicopter-model" value="Bell 205" />
                </div>
            </div>
        </div>


    </div>

</div>
@endsection