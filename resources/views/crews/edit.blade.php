@extends('../layouts.application_layout')

<?php
function drawOneHelicopterForm($index, $helicopter, $template = false) {
    // $index       integer     The array index to use when submitting this form
    // $helicopter  Helicopter  A Helicopter model to populate this form with - ['tailnumber'=>'N12345', 'model'=>'Bell 205']
    // $template    boolean     If TRUE, this function will draw the blank template for a Helicopter Form rather than a populated form.
    if($template) {
        $helicopter = new App\Helicopter(array("tailnumber"=>"","model"=>""));
        $index = "";
    }
    $output = "<div class=\"crew-helicopter-form";
    if($template) $output .= " dynamic-form-template";
    $output .= "\">
        <div class=\"form-group\">
            <label for=\"helicopter-tailnumber\" class=\"col-xs-12 col-sm-2 control-label\">Tailnumber</label>
            <div class=\"form-inline col-xs-8 col-sm-6\">
                <span><input type=\"text\" class=\"form-control helicopter-tailnumber\" name=\"crew[helicopters][".$index."][tailnumber]\" value=\"".$helicopter->tailnumber."\" ";

    if(!$template) $output .= "readonly ";

    $output .= "/></span>\n";
    
    if(!$template) $output .= "<span><button class=\"btn btn-default release-helicopter-button\" data-helicopter-id=\"".$index."\" type=\"button\">Release</button></span>\n";
     
     $output .= "</div>
        </div>

        <div class=\"form-group\">
            <label for=\"helicopter-model\" class=\"col-xs-12 col-sm-2 control-label\">Make/Model</label>
            <div class=\"form-inline col-xs-8 col-sm-6\">
                <input type=\"text\" class=\"form-control helicopter-model\" name=\"crew[helicopters][".$index."][model]\" value=\"".$helicopter->model."\" />
            </div>
        </div>\n";

    if(!$template) $output .= " <div class=\"form-group\">
                                <div class=\"col-sm-2\"></div>
                                <div class=\"col-sm-6\">
                                    <a href=\"".route('new_status_for_helicopter',$helicopter->tailnumber)."\" class=\"btn btn-default\" role=\"button\">Go to the Status Page</a>
                                </div></div>\n";

    $output .= "</div>\n";

    echo $output;
}
?>




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
            <input type="hidden" name="crew_id" value="{{ $crew->id }}" />
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
                <div class="col-sm-2">
                    <label for="add-helicopter-button" class="control-label sr-only">Add a Helicopter</label>
                    <button class="btn btn-default" id="add-helicopter-button" type="button" title="Assign another helicopter to this crew">Add a Helicopter</button>
                </div>
            </div>
            <?php $i = 0; ?>
            @foreach($crew->helicopters as $helicopter)
                <?php drawOneHelicopterForm($i,$helicopter); ?>
            <?php $i++; ?>
            @endforeach

            <div id="dynamic-form-insert-placeholder" style="display:none;"></div>

            <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-default">Save</button>
                </div>
            </div>
        </form>

        <?php drawOneHelicopterForm(null,null,true); ?>

        <div id="helicopter-index" style="display:none;">{{ $i }}</div>


    </div>

</div>
@endsection

@section('scripts-postload')
    @parent
    <script>
        function withoutInvalidChars(str) {
            return str.replace("/","").replace("\\","").replace("\"","").replace("'","").replace("?","").replace("=","").replace(" ","");
        }

        function setStatusForAddButton() {
            // Disable the "Add a Helicopter" button if there are any blank "Tailnumber" fields on the helicopter form
            // Enable the button if there are no blank "Tailnumber" fields
            var blank_field_exists = false;
            $(".form").children(".helicopter-tailnumber").each(function( i ) {

                if($(this).val() == "") {
                    blank_field_exists = true;
                }
            });

            if(blank_field_exists) {
                    $('#add-helicopter-button').attr("disabled",true).prop("title","Fill in the existing helicopter form before adding another.");
            } else {
                $('#add-helicopter-button').attr("disabled",false).prop("title","Assign another helicopter to this crew");
            }
        }
    </script>
    <script>
        (function() {
            // Add click behavior to the 'Add Helicopter' button
            $('#add-helicopter-button').click(function() {
                // Get the next array index to stuff a helicopter into
                var i = parseInt($('#helicopter-index').html());

                // Copy the helicopter form template into the active form
                var newForm = $(".dynamic-form-template").clone().removeClass('dynamic-form-template');
                newForm.find('.helicopter-tailnumber').prop("name","crew[helicopters]["+i+"][tailnumber]")
                newForm.find('.helicopter-model').prop("name","crew[helicopters]["+i+"][model]");
                newForm.find('.release-helicopter-button').attr("data-helicopter-id",i);
                newForm.insertBefore('#dynamic-form-insert-placeholder');

                // Increment the 'helicopter-index' div
                $('#helicopter-index').html(i+1);

                // Disable the "Add Helicopter" button. The form listener will take care of re-enabling it when appropriate
                $('#add-helicopter-button').attr("disabled",true).prop("title","Fill in the existing helicopter form before adding another.");
            });
            
            // Disable the "Add Helicopter" button if a blank "tailnumber" field exists anywhere in the form
            // Or enable the button if text is typed into a blank tailnumber field
            $("#edit_crew_form").on("keyup",".helicopter-tailnumber", function(event) {
                setStatusForAddButton();
            });

            // Add click behavior to the "Release" helicopter button
            $("#edit_crew_form").on("click",".release-helicopter-button", function(event) {
                
                // Get the tailnumber of the helicopter to release
                var parent = $(this).parents('.crew-helicopter-form');
                var tailnumber = withoutInvalidChars(parent.find('.helicopter-tailnumber').val().trim());
                var csrf_token = $(this).parents('form').children("input[name='_token']").val();
                var crew_id = $("input[name='crew_id']").val();

                if(tailnumber == "") {
                    // If no tailnumber has been specified, simply remove this entry from the page
                    parent.hide(300,function(){ this.remove(); });
                    setStatusForAddButton();
                } else {
                    // Send AJAX request to release this helicopter from this crew
                    $.ajax({
                        url: "/helicopters/"+encodeURIComponent(tailnumber)+"/release",
                        type: "post",
                        data: {"_token":csrf_token, "sent-from-crew":crew_id}
                    }).done(function() {
                        // Success
                        parent.hide(300,function(){ this.remove(); });
                    }).always(function(xhr,status) {
                        console.log("AJAX status: "+status);
                    });
                }
                
            });

        })();
    </script>
@endsection