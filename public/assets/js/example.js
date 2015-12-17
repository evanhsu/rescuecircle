<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> <html>     <head>     <meta http-equiv="Content-Type" content="text/html; charset=utf-8">     <meta http-equiv="X-UA-Compatible" content="IE=7, IE=9" />     <!--The viewport meta tag is used to improve the presentation and behavior of the     samples on iOS devices-->     <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no"/>     <title>       Flickr     </title>     <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/2.7/js/dojo/dijit/themes/claro/claro.css"/>     <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/2.7/js/esri/dijit/css/Popup.css"/>     <style>       html, body { height: 100%; width: 100%; margin: 0; padding: 0; } .esriScalebar{       padding: 20px 20px; } #map{ padding:0;}     </style>     <script type="text/javascript">       var dojoConfig = {         parseOnLoad: true       };     </script>     <script type="text/javascript" src="http://serverapi.arcgisonline.com/jsapi/arcgis/?v=2.7">     </script>     <script type="text/javascript">       
dojo.require("dijit.layout.BorderContainer");       
dojo.require("dijit.layout.ContentPane");       
dojo.require("esri.map");       
dojo.require("esri.layers.FeatureLayer");       
dojo.require("esri.dijit.Popup");         
var map, featureLayer;        
function init() {         
//setup the map's initial extent          
var initExtent = new esri.geometry.Extent({"xmin":-16305479,"ymin":-635073,"xmax":5884495,"ymax":8307447,"spatialReference":{"wkid":102100}});          
//create a popup to replace the map's info window         
var popup = new esri.dijit.Popup(null, dojo.create("div"));         
map = new esri.Map("map", {           
	extent: initExtent,           
	infoWindow: popup
});

dojo.connect(map, 'onLoad', function(theMap) {          
	//resize the map when the browser resizes           
	dojo.connect(dijit.byId('map'), 'resize', map,map.resize);            
	// get photos from flickr then create a feature layer           
	requestPhotos();         
});          

//Add the imagery layer to the map. View the ArcGIS Online site for services http://arcgisonline/home/search.html?t=content&f=typekeywords:service             
var basemap = new esri.layers.ArcGISTiledMapServiceLayer("http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer");         
map.addLayer(basemap);       
}        

function requestPhotos(){         
	//get geotagged photos from flickr         
	//tags=flower&tagmode=all         
	var requestHandle = esri.request({           
		url: "http://api.flickr.com/services/feeds/geo?&format=json",           
		callbackParamName: "jsoncallback",           
		load: requestSucceeded,           
		error: requestFailed         
	}, {           
		useProxy: false         
	});       
}              

function requestSucceeded(response, io) {         
	//loop through the items and add to the feature layer         
	var features = [];         

	dojo.forEach(response.items, function(item, idx) {           
		var attr = {};           
		attr["description"] = item.description;           
		attr["title"] = item.title ? item.title : "Flickr Photo";           
		attr["ObjectID"] = idx;            
		var geometry = esri.geometry.geographicToWebMercator(new esri.geometry.Point(item.longitude, item.latitude, map.spatialReference));            
		var graphic = new esri.Graphic(geometry);           
		graphic.setAttributes(attr);           
		features.push(graphic);         
	});          

	//create a feature collection for the flickr photos         
	var featureCollection = {           
		"layerDefinition": null,           
		"featureSet": {             
			"features": features,             
			"geometryType": "esriGeometryPoint"           
		}         
	};                  

	featureCollection.layerDefinition = {           
		"geometryType": "esriGeometryPoint",           
		"objectIdField": "ObjectID",           
		"drawingInfo": {             
			"renderer": {               
				"type": "simple",               
				"symbol": {                 
					"type": "esriPMS",                 
					"url": "http://dl.dropbox.com/u/2654618/flickr.png",                 
					"contentType": "image/png",                 
					"width": 15,                 
					"height": 15               
				}             
			}           
		},           
		"fields": [{             
			"name": "ObjectID",             
			"alias": "ObjectID",             
			"type": "esriFieldTypeOID"           
		},{             
			"name": "description",             
			"alias": "Description",             
			"type": "esriFieldTypeString"           
		},{             
			"name": "title",             
			"alias": "Title",             
			"type": "esriFieldTypeString"           
		}]         
	};          

	//define a popup template         
	var popupTemplate = new esri.dijit.PopupTemplate({           
		title: "{title}",           
		description:"{description}"         
	});        

	//create a feature layer based on the feature collection         
	featureLayer = new esri.layers.FeatureLayer(featureCollection, {           
		id: 'flickrLayer',           
		infoTemplate: popupTemplate         
	});          
	map.addLayer(featureLayer);                  

	//associate the features with the popup on click         
	dojo.connect(featureLayer,"onClick",function(evt){            
		map.infoWindow.setFeatures([evt.graphic]);         
	});       
}        

function requestFailed(error) {         console.log('failed');       }       dojo.ready(init);     </script>   </head>   <body class="claro">     <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design:'headline'"     style="width: 100%; height: 100%; margin: 0;">       <div id="map" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center'"       style="border:1px solid #000;padding:0;">       </div>     </div>   </body>  </html>  
