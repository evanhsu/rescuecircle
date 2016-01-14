@extends('layouts.application_layout')


@section('title','RescueCircle')

@section('stylesheets')
    @parent
    <link rel="stylesheet" href="http://js.arcgis.com/3.14/esri/css/esri.css">
    <link rel="stylesheet" href="/assets/css/map-popup.css">
    <!-- <link rel="stylesheet" href="/assets/css/esri.css">--> <!-- Use the local version for offline development -->
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
@endsection


@section('content')
    <div id="container-fluid" class="container-fluid">
        <div id="mapDiv">
        <!-- ArcMap gets placed here -->
        </div> <!-- /mapDiv -->
        <div id="legendContainer">
            <div id="legendDiv"></div>
        </div>
        <button type="button" class="btn btn-default" id="legendToggler">
            <span id="left-arrow" class="glyphicon glyphicon-triangle-left" style="display:none"></span>
            <span id="right-arrow" class="glyphicon glyphicon-triangle-right"></span>
            Layers
        </button>
    </div>
@endsection

@section('scripts-postload')
    @parent
    <script src="http://js.arcgis.com/3.14/"></script>  <?php /* ArcGIS API - must load before other classes */?>
    <?php /* <script src="assets/js/arcmap.js"></script> */ ?>
    <script>
        $(document).ready(function() {
            $('#legendToggler').click(function() {
                $('#legendDiv').toggle("fast");
                $('#left-arrow').toggle();
                $('#right-arrow').toggle();
            });
            $('#legendDiv').click(function() {
                $('#legendDiv').toggle("fast");
                $('#left-arrow').toggle();
                $('#right-arrow').toggle();
            });
        });
    </script>
    <script src="assets/js/localmap.js"></script>         <?php /* Render the map and all layers - waits for the DOM to load so dependencies will always load first */?>
@endsection