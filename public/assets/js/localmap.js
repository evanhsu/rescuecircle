// This script gathers all of the map data and sends the
// rendered map to the requested DIV

// Configuration variables
var mapDiv = "mapDiv";
var arcServerUrl = "https://egp.nwcg.gov/arcgis/rest/services/FireCOP/ShortHaul/FeatureServer/0";


// Send AJAX request to retrieve all active Fire Resources

// Create a jQuery Deferred to notify when the map has finished loading
var loadingMap = $.Deferred(); 
var loadingFeatures = $.Deferred(); 

// Retrieve all FireResources to plot on the map
// Track completion of the ajax request with the 'loadingFireResources' deferred
var fireResources;
var loadingFireResources = $.ajax({
                                    url: "/status/all",
                                    type: "get",
                                    dataType: 'json'

                                }).done(function(o) {
                                    fireResources = o;
                                    // console.log(o);
                                }).fail(function(e) {
                                    // An error occurred
                                    console.error("Status Code: "+e.status+",  Text: "+e.statusText);
                                });

// Assemble and render the entire map
var map;    // Accessible in the global scope
require([   "esri/map",
            "esri/config", "esri/urlUtils",
            "esri/dijit/Popup", "esri/dijit/PopupMobile", "esri/dijit/PopupTemplate",
            "esri/dijit/Legend",
            "esri/InfoTemplate",
            "dojo/dom-construct", "dojo/dom-style",
            "esri/layers/GraphicsLayer",
            "assets/js/Helicopter",
            "dojo/domReady!",
        ], function(    Map, 
                        esriConfig, urlUtils,
                        Popup, PopupMobile, PopupTemplate,
                        Legend,
                        InfoTemplate,
                        domConstruct, domStyle,
                        GraphicsLayer,
                        Helicopter
                    ) {

    // Define the default popup settings and size for every feature that is clicked on
    var popup = new Popup({
        fillSymbol: false,
        titleInBody: false
    }, domConstruct.create("div"));
    popup.resize(450,200);

    var infoTemplate = new InfoTemplate("${popuptitle}","${popupcontent}");
    
    // Grab the basemap, set the initial map view, and load the map into the specified DOM element ("mapDiv")
    map = new Map(mapDiv, {
      center: [-113, 45],
      zoom: 6,
      basemap: "topo",
      showLabels: true,
      infoWindow: popup
    });

    map.on('load',function() {
        loadingMap.resolve(); // Resolve this deferred object (mark this task as complete and fire callbacks)
    });


    var gl1 = new GraphicsLayer({ id: "shorthaulhelicopters", infoTemplate: infoTemplate });    // This layer holds the short haul helicopters
    var gl2 = new GraphicsLayer({ id: "circles" });                 // This layer holds the 100nm distance rings around each short haul helicopter
    var gl3 = new GraphicsLayer({ id: "rappelhelicopters" });       // This layer holds the rappel helicopters
    var gl4 = new GraphicsLayer({ id: "crews" });                   // This layer holds the hotshot crews
    var gl5 = new GraphicsLayer({ id: "smokejumperairplanes" });    // This layer holds the smokejumper airplanes


    //Add each Feature point to the GraphicsLayer
    var p,heliGraphic,responseRingGraphic,c,heli;
    
    // Wait for the map to load AND the fireResource data to load, THEN draw icons on the map...
    $.when( loadingMap,
            loadingFireResources).then(
            
        function() {
            // Add our layers to the map
            map.addLayer(gl1);
            map.addLayer(gl2);
            map.addLayer(gl3);
            map.addLayer(gl4);
            map.addLayer(gl5);

            // Add the Legend to the map - this must be done AFTER all of the data layers have been added
            // so that it knows which symbols to describe.
            var legend = new Legend({
                map: map
            }, "legendDiv");
            legend.startup();

            
            // Draw each helicopter on the map and place a 100nm ring around it
            for(var i=0; i < fireResources.length; i++) {
                heli = new Helicopter(fireResources[i]);

                gl1.add(heli.mapGraphic());              // Add a helicopter icon to the appropriate GraphicsLayer
                if(heli.showResponseRing()) {
                    gl2.add(heli.mapResponseRingGraphic());  // Add a circle to a different GraphicsLayer to represent the response range for this helicopter
                }
            }
            
/*
            // Add 'click' behavior to the map
            map.on("click", function(e) {
                gl2.hide();
            });
*/
        });


}); // End require()

