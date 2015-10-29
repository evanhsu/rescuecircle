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
                        Units
                    ) { 
    var map = new Map(mapDiv, {
      center: [-113, 45],
      zoom: 6,
      basemap: "topo"
    });

    var gl1 = new GraphicsLayer({ id: "helicopters" }); // This layer holds the helicopters
    var gl2 = new GraphicsLayer({ id: "circles" });     // This layer holds the 100nm distance rings 
    
    var simpleMarker = new SimpleMarkerSymbol().setSize(30);
    var heliMarker = new PictureMarkerSymbol('assets/images/heli-icon.png',75,75);

    // Styling for the 100nm circle around each helicopter

    // Styling for a Response Circle for an ACTIVE helicopter (updated within the last 24 hours)
    var responseCircleSymbolActive = new SimpleFillSymbol(
                                            SimpleFillSymbol.STYLE_NULL,
                                            new SimpleLineSymbol(   esri.symbol.SimpleLineSymbol.STYLE_SHORTDOT,
                                                                    new Color([100,200,100]), 
                                                                    3),
                                            null /* Fill-color for circle */
                                        );

    // Styling for a Response Circle for an INACTIVE helicopter (NOT updated within the last 24 hours)
    var responseCircleSymbolInactive = new SimpleFillSymbol(
                                            SimpleFillSymbol.STYLE_NULL,
                                            new SimpleLineSymbol(   esri.symbol.SimpleLineSymbol.STYLE_SHORTDOT,
                                                                    new Color([150,150,150]), 
                                                                    3),
                                            null /* Fill-color for circle */
                                        );

    var responseCircleParams = {    radius: 100,
                                    radiusUnit: Units.NAUTICAL_MILES,
                                    numberOfPoints: 120,
                                    geodesic: true };

    //Add each point to the GraphicsLayer
    var p,heliGraphic,responseCircleGraphic,c,heli;
    
    map.addLayer(gl1);
    map.addLayer(gl2);
    map.on("load", function() {

        // Draw each helicopter on the map and place a 100nm ring around it
        for(var i=0; i < heliLocations.helicopters.length; i++) {
            heli = heliLocations.helicopters[i];

            p = new Point(Number(heli.longitude),Number(heli.latitude));
            c = new Circle(p, responseCircleParams);
/*
            if(heli.upToDate()) {
                // Use symbology for an ACTIVE helicopter (bright colors)
                heliGraphic= new Graphic(p,heliMarkerActive);
                responseCircleGraphic = new Graphic(c, responseCircleSymbolActive);
            }
            else {
                // Use symbology for an INACTIVE helicopter (dim colors)
                heliGraphic= new Graphic(p,heliMarkerInactive);
                responseCircleGraphic = new Graphic(c, responseCircleSymbolInactive);
            }
*/
            heliGraphic= new Graphic(p,heliMarker);
            responseCircleGraphic = new Graphic(c, responseCircleSymbolActive);

            gl1.add(heliGraphic);           // Add the helicopter to the "helicopters" GraphicsLayer
            gl2.add(responseCircleGraphic); // Add the distance ring to the "circles" GraphicsLayer
        }
        
    });
  });
