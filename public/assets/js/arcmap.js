// This script gathers all of the map data and sends the
// rendered map to the requested DIV

// Configuration variables
var mapDiv = "mapDiv";
var arcServerUrl = "https://egp.nwcg.gov/arcgis/rest/services/FireCOP/ShortHaul/FeatureServer/0";
var token = "s-y9e0VGlILUm5TCdnY1c6aaIzbcSGJWC99LdLja8GtV4BgYCpIjZHBGmxsq7VL7"; // evanhsu@96.41.152.69 (expires 12/21/2015)

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
/*var fireResourcesArcLayer;
$.ajax({
    url: arcServerUrl,
    type: "get",
    dataType: "json"
}).done(function(o) {
    fireResourcesArcLayer = o;
    console.log(o);
}).fail(function(e) {
    // An error occurred
    console.error("Status Code: "+e.status+",  Text: "+e.statusText+",  Status: "+e.status);
});*/

// Assemble and render the entire map
var map;    // Accessible in the global scope
require([   "esri/map",
            "esri/dijit/Popup", "esri/dijit/PopupMobile", "esri/dijit/PopupTemplate",
            "esri/dijit/Legend",
            "dojo/dom-construct", "dojo/dom-style",
            "assets/js/ShortHaulFeatureLayer",
            "dojo/domReady!",
        ], function(    Map, 
                        Popup, PopupMobile, PopupTemplate,
                        Legend,
                        domConstruct, domStyle,
                        ShortHaulFeatureLayer
                    ) {
    // Define the default popup settings and size for every feature that is clicked on
    var popup = new Popup({
        fillSymbol: false,
        titleInBody: false
    }, domConstruct.create("div"));
    popup.resize(450,200);
    
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


    // var gl1 = new GraphicsLayer({ id: "helicopters" }); // This layer holds the helicopters
    // var gl2 = new GraphicsLayer({ id: "circles" });     // This layer holds the 100nm distance rings
    var fl = new ShortHaulFeatureLayer(arcServerUrl+"?token="+token);
    fl.featureLayer.on('load',function(layer) {
        loadingFeatures.resolve();
        console.error(layer.layer.graphics);
    });


    //Add each Feature point to the GraphicsLayer
    var p,heliGraphic,responseRingGraphic,c,heli;
    
    // Wait for the map to load AND the fireResource data to load, THEN draw icons on the map...
    $.when( loadingMap,//).then(
            loadingFireResources,
            loadingFeatures).then(
            
        function() {
            // Add our layers to the map
            map.addLayer(fl.featureLayer);
            var legend = new Legend({
                map: map
            }, "legendDiv");
            legend.startup();
            domStyle.set("legendDiv", "position", ""); // The Legend widget automatically adds 'position: relative' to the legend div. This gets rid of that.

            // map.addLayer(gl1);
            // map.addLayer(gl2);
            
/*
            // Draw each helicopter on the map and place a 100nm ring around it
            for(var i=0; i < fireResources.length; i++) {
                heli = new Helicopter(fireResources[i]);
                if(i==1) {
                    heli.fresh = false;
                }
                gl1.add(heli.mapGraphic());              // Add a helicopter icon to the appropriate GraphicsLayer
                gl2.add(heli.mapResponseRingGraphic());  // Add a circle to a different GraphicsLayer to represent the response range for this helicopter

            }
*/
            
/*
            // Add 'click' behavior to the map
            map.on("click", function(e) {
                gl2.hide();
            });
*/
        });


}); // End require()

