// This script gathers all of the map data and sends the
// rendered map to the requested DIV

// Configuration variables
var mapDiv = "mapDiv";
var arcServerUrl = "https://egp.nwcg.gov/arcgis/rest/services/FireCOP/ShortHaul/FeatureServer/0";

var token = "RIWfM77CGFKvsk7PGTqfI0_M2BvaD5LMEs8LqeYQJG6FsCTOwUGgqOOFdje2uVdk"; // evanhsu@96.41.152.69 (expires 12/17/2016)

// Send AJAX request to retrieve all active Fire Resources

// Create a jQuery Deferred to notify when the map has finished loading
var loadingMap = $.Deferred(); 

// Retrieve all FireResources to plot on the map
// Track completion of the ajax request with the 'loadingFireResources' deferred
var fireResources;
var loadingFireResources = $.ajax({
                                    url: "/status/all",
                                    type: "get",
                                    dataType: 'json'

                                }).done(function(o) {
                                    fireResources = o;
                                    //console.log(o);
                                }).fail(function(e) {
                                    // An error occurred
                                    console.error("Status Code: "+e.status+",  Text: "+e.statusText+",  Status: "+e.status);
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
            "esri/Color",
            "esri/geometry/Point",
            "esri/geometry/Circle",
            "esri/symbols/SimpleMarkerSymbol",
            "esri/symbols/PictureMarkerSymbol",
            "esri/symbols/SimpleFillSymbol",
            "esri/symbols/SimpleLineSymbol",
            "esri/symbols/TextSymbol",
            "esri/graphic",
            "esri/layers/GraphicsLayer",
            "esri/layers/FeatureLayer",
            "esri/layers/LabelClass",
            "esri/units",
            "assets/js/Helicopter",
            "dojo/domReady!",
        ], function(    Map, 
                        Color,
                        Point, 
                        Circle, 
                        SimpleMarkerSymbol, 
                        PictureMarkerSymbol, 
                        SimpleFillSymbol,
                        SimpleLineSymbol,
                        TextSymbol,
                        Graphic, 
                        GraphicsLayer,
                        FeatureLayer,
                        LabelClass,
                        Units,
                        Helicopter
                    ) { 
    map = new Map(mapDiv, {
      center: [-113, 45],
      zoom: 6,
      basemap: "topo",
      showLabels: true
    });

    map.on('load',function() {
        loadingMap.resolve(); // Resolve this deferred object (mark this task as complete and fire callbacks)
    });

    // var gl1 = new GraphicsLayer({ id: "helicopters" }); // This layer holds the helicopters
    // var gl2 = new GraphicsLayer({ id: "circles" });     // This layer holds the 100nm distance rings
    var fl1 = new FeatureLayer(arcServerUrl+"?token="+token, {
                                                                id: "helicopters",
                                                                outFields: ["*"],
                                                                showLabels: true
                                                            });
    var resourceLabelSymbol = new TextSymbol().setColor(new Color("#555"));
        resourceLabelSymbol.font.setSize("14pt");
        resourceLabelSymbol.font.setFamily("arial");

    //this is the very least of what should be set within the JSON  
    var resourceLabelContent = {
      "labelExpressionInfo": {"value": "{statusable_name}"}
    };

    var resourceLabel = new LabelClass(resourceLabelContent);
    resourceLabel.symbol = resourceLabelSymbol;
    

    //Add each point to the GraphicsLayer
    var p,heliGraphic,responseRingGraphic,c,heli;
    
    // Wait for the map to load AND the fireResource data to load, THEN draw icons on the map...
    $.when( loadingMap,
            loadingFireResources).then(
        function() {
            // Add our layers to the map
            // map.addLayer(gl1);
            // map.addLayer(gl2);
            fl1.setLabelingInfo([resourceLabel]);
            map.addLayer(fl1);
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

