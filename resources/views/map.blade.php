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
    </div>
@endsection

@section('scripts-postload')
    @parent
    <script src="http://js.arcgis.com/3.14/"></script>  <?php /* ArcGIS API - must load before other classes */?>
    <script src="assets/js/arcmap.js"></script>         <?php /* Render the map and all layers - waits for the DOM to load so dependencies will always load first */?>
@endsection