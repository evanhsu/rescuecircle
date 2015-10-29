// This script gathers all of the map data and sends the
// rendered map to the requested DIV

// Configuration variables
var mapDiv = "mapDiv";
var mapServer = "";

// Test Data
var heliLocations = {"helicopters":[
                    {"tailnumber":"N123AB", "crewName":"Crew #1", "latitude":"42.1",    "longitude":"-123.1"},
                    {"tailnumber":"N456CD", "crewName":"Crew #2", "latitude":"43.2",    "longitude":"-116.2"},
                    {"tailnumber":"N789EF", "crewName":"Crew #3", "latitude":"44.3",    "longitude":"-110.3"},
                ]};

//console.error(heliLocations);


// Request a basemap from the server


require([
"esri/map", "esri/geometry/Circle", "esri/geometry/Point", "esri/symbols/SimpleFillSymbol", 
"esri/graphic", "esri/layers/GraphicsLayer","dojo/domReady!"
], function(
Map, Circle, Point, SimpleFillSymbol, 
Graphic, GraphicsLayer
) {
var map = new Map("mapDiv", {
  basemap: "topo",
  center: [-120.741, 45.39],
  slider: false,
  zoom: 6
});
var responseCircleSymbol = new SimpleFillSymbol().setColor(null).outline.setColor("blue");
var gl = new GraphicsLayer({ id: "circles" });
p = new Point(-120,45);
map.addLayer(gl);
map.on("load", function() {
  var circle = new Circle(p, {
    radius: 100000
  });
  var graphic = new Graphic(circle, responseCircleSymbol);
  gl.add(graphic);
});
});