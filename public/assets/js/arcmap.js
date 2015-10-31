// This script gathers all of the map data and sends the
// rendered map to the requested DIV

// Configuration variables
var mapDiv = "mapDiv";
var mapServer = "";



// Test Data
var heliLocations = {"helicopters":[
                    {"tailnumber":"N123AB", "crewName":"Crew #1", "latitude":"42.1",    "longitude":"-123.1",   "staffing_emts":"3",    "staffing_shorthaul":"6"},
                    {"tailnumber":"N456CD", "crewName":"Crew #2", "latitude":"43.2",    "longitude":"-116.2",   "staffing_emts":"4",    "staffing_shorthaul":"7"},
                    {"tailnumber":"N789EF", "crewName":"Crew #3", "latitude":"44.3",    "longitude":"-110.3",   "staffing_emts":"5",    "staffing_shorthaul":"6"},
                ]};

// console.error(heliLocations);


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
            "esri/graphic",
            "esri/layers/GraphicsLayer",
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
                        Graphic, 
                        GraphicsLayer,
                        Units,
                        Helicopter
                    ) { 
    map = new Map(mapDiv, {
      center: [-113, 45],
      zoom: 6,
      basemap: "topo"
    });

var gl1 = new GraphicsLayer({ id: "helicopters" }); // This layer holds the helicopters
var gl2 = new GraphicsLayer({ id: "circles" });     // This layer holds the 100nm distance rings

//Add each point to the GraphicsLayer
var p,heliGraphic,responseRingGraphic,c,heli;

map.addLayer(gl1);
map.addLayer(gl2);
map.on("load", function() {

    // Draw each helicopter on the map and place a 100nm ring around it
    for(var i=0; i < heliLocations.helicopters.length; i++) {
        heli = new Helicopter(heliLocations.helicopters[i]);
        if(i==1) {
            heli.fresh = false;
        }
        gl1.add(heli.mapGraphic());     // Add a helicopter icon to the appropriate GraphicsLayer
        gl2.add(heli.mapResponseRingGraphic()); // Add a circle to a different GraphicsLayer to represent the response range for this helicopter

    }
    
}); // End map.on(load)
map.on("click", function(e) {
    //gl2.hide();
});

}); // End require()
