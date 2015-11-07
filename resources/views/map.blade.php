@extends('layouts.application_layout')


@section('title','RescueCircle')

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="http://js.arcgis.com/3.14/esri/css/esri.css">
@endsection


@section('scripts-preload')
    @parent
    <script>
        // Tell Dojo where to find custom modules for the ArcGIS API
        var dojoConfig = { 
            locale: "en",
            packages: [{
                name: "assets",
                //location: location.pathname.replace(/\/[^/]+$/, "") + "/assets/js"
                location: "/assets"
            }]
        };
    </script>
    <script src="http://js.arcgis.com/3.14/"></script>  <?php /* ArcGIS API - must load before other classes */?>
    <script src="assets/js/arcmap.js"></script>         <?php /* Render the map and all layers - waits for the DOM to load so dependencies will always load first */?>
    <script>
        (function() {
            $("#flash").show().delay(5000).fadeOut();   //Fails silently if #flash doesn't exist
        })();
    </script>
@endsection
    

@section('content')
    <div id="container-fluid" class="container-fluid">
        <div id="mapDiv">
        <!-- ArcMap gets placed here -->
        </div> <!-- /mapDiv -->
    </div>
@endsection