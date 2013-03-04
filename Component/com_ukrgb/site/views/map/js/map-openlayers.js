/**
 * 
 */

window.addEvent("domready", function() {
	Proj4js.defs["EPSG:27700"] = "+proj=tmerc +lat_0=49 +lon_0=-2 +k=0.9996012717 +x_0=400000 +y_0=-100000 +ellps=airy +datum=OSGB36 +units=m +no_defs";
	var key = "CE783C03FD1F8AE2E0405F0AC8600A1C";
	var WGS84Proj = new OpenLayers.Projection("EPSG:4326")  ;      
	//var OSGBProj = new OpenLayers.Projection("EPSG:27700");

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
        graphicOpacity : 0.4
        
    });
        
	//map.addLayers([osmap,vectorLayer]);	// OS Open Spave
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
            '<a href=" index.php?option=com_content&id='+feature.attributes.riverguide+'&view=article">River Guide</a>',
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


});
