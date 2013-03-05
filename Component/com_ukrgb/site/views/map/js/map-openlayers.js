/**
 * 
 */

window.addEvent("domready", function() {
	Proj4js.defs["EPSG:27700"] = "+proj=tmerc +lat_0=49 +lon_0=-2 +k=0.9996012717 +x_0=400000 +y_0=-100000 +ellps=airy +datum=OSGB36 +units=m +no_defs";
	var key = "CE783C03FD1F8AE2E0405F0AC8600A1C";
	var WGS84Proj = new OpenLayers.Projection("EPSG:4326")  ;      
	var OSGBProj = new OpenLayers.Projection("EPSG:27700");

	var mapData = params.mapdata;
	var url = params['url'];


	var map = new OpenLayers.Map({
        div: "map",
        allOverlays: false,
        projection: new OpenLayers.Projection("EPSG:900913")
    });
    
	var osmlayer = new OpenLayers.Layer.OSM( "Simple OSM Map");
	var osmap = new OpenLayers.Layer.OpenSpace("OS Openspace",key, {});

	//  Marker styles
    var markerSize = {
        'graphicHeight': 16,
        'graphicWidth': 16
    };
    var defaultMarkerStyle = Object.merge({
        'externalGraphic': OpenLayers.Util.getImagesLocation() + "marker.png"
    }, markerSize);
    
    var secondaryMarkerStyle = Object.merge({
        'graphicOpacity' : 0.4
    }, defaultMarkerStyle);
    
    /*var blueMarkerStyle = Object.merge({
        'externalGraphic': OpenLayers.Util.getImagesLocation() + "marker-blue.png"
    }, markerSize);
	*/
    var styleMap = new OpenLayers.StyleMap({
        'default': new OpenLayers.Style(defaultMarkerStyle)
    });

    // Vector layer (with default marker style)
    var vectorLayer = new OpenLayers.Layer.Vector("This River", {
        styleMap: styleMap
        
    });
    var otherVectorLayer = new OpenLayers.Layer.Vector("Other Rivers", {
        styleMap: styleMap,        
    });
        
	//map.addLayers([osmap,vectorLayer,otherVectorLayer]);	// OS Open Spave
	map.addLayers([osmlayer,vectorLayer,otherVectorLayer]); // Open Street Map
    map.addControl(new OpenLayers.Control.LayerSwitcher());
    
    // make markers selectable (popups)
    var selectControl = new OpenLayers.Control.SelectFeature([vectorLayer,otherVectorLayer]);
    map.addControl(selectControl);
    selectControl.activate();

	
	vectorLayer.events.on({
        'featureselected': onFeatureSelect,
        'featureunselected': onFeatureUnselect
        });
	otherVectorLayer.events.on({
		'featureselected': onFeatureSelect,
        'featureunselected': onFeatureUnselect
        });
	
			
	var area = new OpenLayers.Bounds(
			parseFloat(mapData.w_lng),
			parseFloat(mapData.s_lat),
			parseFloat(mapData.e_lng),
			parseFloat(mapData.n_lat));
	map.zoomToExtent(area.transform(WGS84Proj, map.getProjectionObject()));
	
	switch (mapData.map_type)  {
    case "0" : // everything
		var r = new Request.JSON({url: url,
			onSuccess: function(mapPoints){
				// mapPoints processing
				for (var i = 0; i < mapPoints.length; i++){
					var name = mapPoints[i].description;
					var pos = name.indexOf(' - ');
					var t = name.substring(0,pos);
					var d = name.substring(pos+3);					
					if (pos == -1){t = name;d = '';}
						
				    if (mapData.aid == mapPoints[i].riverguide){
					    var feature = new OpenLayers.Feature.Vector(
					    		new OpenLayers.Geometry.Point(mapPoints[i].X, mapPoints[i].Y).transform(WGS84Proj, map.getProjectionObject()), {
					    	        title: t,
					    	        description: d,
					    	        riverguide: mapPoints[i].riverguide
					    	    });
					    
				    	vectorLayer.addFeatures(feature);
				    }
				    else {
				    	var feature = new OpenLayers.Feature.Vector(
					    		new OpenLayers.Geometry.Point(mapPoints[i].X, mapPoints[i].Y).transform(WGS84Proj, map.getProjectionObject()), {
					    	        title: t,
					    	        description: d,
					    	        riverguide: mapPoints[i].riverguide
					    	    },secondaryMarkerStyle);
					    
				    	otherVectorLayer.addFeatures(feature);
				    }
				    
				}
			}}).get({'task':'mappoints','type': mapData.map_type});
    	break;

    default: 	
    	console.log("Not implemented",mapData.map_type);
    }
	
	
    
    
    
    
    // popup event handlers
    function onPopupClose(evt) {
        // 'this' is the popup.
        selectControl.unselect(this.feature);
    }

    function onFeatureSelect(evt) {
        feature = evt.feature;
        var d = feature.attributes.description;
        if (d != ''){d += '<br>';}
        popup = new OpenLayers.Popup.FramedCloud("featurePopup",
        feature.geometry.getBounds().getCenterLonLat(),
        new OpenLayers.Size(100, 100),
            "<strong>" + feature.attributes.title + "</strong><br>" + d +
            '<a href="/index.php?option=com_content&id='+feature.attributes.riverguide+'&view=article">River Guide</a>',
        null, true, onPopupClose);

        feature.popup = popup;
        popup.feature = feature;
        map.addPopup(popup);
    }

    function onFeatureUnselect(evt) {
        feature = evt.feature;
        if (feature.popup) {
            popup.feature = null;
            map.removePopup(feature.popup);
            feature.popup.destroy();
            feature.popup = null;
        }
    }
    
    // Mouse Click
    OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
        defaultHandlerOptions: {
            'single': true,
            'double': false,
            'pixelTolerance': 0,
            'stopSingle': false,
            'stopDouble': false
        },

        initialize: function(options) {
            this.handlerOptions = OpenLayers.Util.extend(
                {}, this.defaultHandlerOptions
            );
            OpenLayers.Control.prototype.initialize.apply(
                this, arguments
            ); 
            this.handler = new OpenLayers.Handler.Click(
                this, {
                    'click': this.trigger
                }, this.handlerOptions
            );
        }, 

        trigger: function(e) {
            var lonlat = map.getLonLatFromPixel(e.xy).transform(map.getProjectionObject(),OSGBProj);
            //alert("You clicked near: " + gridrefNumToLet(lonlat.lon,lonlat.lat,6));
            $("GridRef").value = gridrefNumToLet(lonlat.lon,lonlat.lat,6);
        }
    
       
    });
    
    var click = new OpenLayers.Control.Click();
    map.addControl(click);
    click.activate();

    
    // Mouse Hover
    
    OpenLayers.Control.Hover = OpenLayers.Class(OpenLayers.Control, {                
        defaultHandlerOptions: {
            'delay': 500,
            'pixelTolerance': null,
            'stopMove': false
        },

        initialize: function(options) {
            this.handlerOptions = OpenLayers.Util.extend(
                {}, this.defaultHandlerOptions
            );
            OpenLayers.Control.prototype.initialize.apply(
                this, arguments
            ); 
            this.handler = new OpenLayers.Handler.Hover(
                this,
                {'pause': this.onPause, 'move': this.onMove},
                this.handlerOptions
            );
        }, 

        onPause: function(e) {
            var lonlat = map.getLonLatFromPixel(e.xy);
            $("Lat").value = lonlat.lat;
            $("Lng").value = lonlat.lon;
            var osgblnglat = lonlat.transform(map.getProjectionObject(),OSGBProj);
            $("GridRef").value = gridrefNumToLet(osgblnglat.lon,osgblnglat.lat,6);
        },

        onMove: function(e) {
            // if this control sent an Ajax request (e.g. GetFeatureInfo) when
            // the mouse pauses the onMove callback could be used to abort that
            // request.
        }
    });
    
    var hover = new OpenLayers.Control.Hover({
        handlerOptions: {
            'delay': 1
        }
    });
    map.addControl(hover);
    hover.activate();

    
    
    
    
    
    
});

/* The folowing is reused form :-  Convert latitude/longitude <=> OS National Grid Reference points (c) Chris Veness 2002-2010   
 * 
 * convert numeric grid reference (in metres) to standard-form grid ref
*/
function gridrefNumToLet(e, n, digits) {
  // get the 100km-grid indices
  var e100k = Math.floor(e / 100000), n100k = Math.floor(n / 100000);

  if (e100k < 0 || e100k > 6 || n100k < 0 || n100k > 12) return "";

  // translate those into numeric equivalents of the grid letters
  var l1 = (19 - n100k) - (19 - n100k) % 5 + Math.floor((e100k + 10) / 5);
  var l2 = (19 - n100k) * 5 % 25 + e100k % 5;

  // compensate for skipped "I" and build grid letter-pairs
  if (l1 > 7) l1++;
  if (l2 > 7) l2++;
  var letPair = String.fromCharCode(l1 + "A".charCodeAt(0), l2 + "A".charCodeAt(0));

  // strip 100km-grid indices from easting & northing, and reduce precision
  e = Math.floor((e % 100000) / Math.pow(10, 5 - digits / 2));
  n = Math.floor((n % 100000) / Math.pow(10, 5 - digits / 2));
  // note use of floor, as ref is bottom-left of relevant square!

  var gridRef = letPair + " " + e.padLZ(digits / 2) + " " + n.padLZ(digits / 2);

  return gridRef;
}
/*
* pad a number with sufficient leading zeros to make it w chars wide
*/
Number.prototype.padLZ = function(width) {
  var num = this.toString(), len = num.length;
  for (var i = 0; i < width - len; i++) num = "0" + num;
  return num;
}