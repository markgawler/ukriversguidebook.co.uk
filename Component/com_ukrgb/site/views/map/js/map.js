window.addEvent("domready", function() {
	// Create a new map instance
	var map = L.map('map');
	var marker = new Array();
	

	mapData = params.mapdata;
	map.setView([mapData.lat, mapData.long], mapData.zoom);
	map.on('zoomend', function(e) {
		var bounds = map.getBounds();
		
		var r = new Request.JSON({url: params.url,
			onSuccess: function(mapPoints){
				// mapPoints processing
				for (var i = 0; i < mapPoints.length; i++){
					var p = mapPoints[i].id;
					if (marker[p] == null){
						marker[p] = L.marker([mapPoints[i].X, mapPoints[i].Y]).addTo(map);
						marker[p].bindPopup(mapPoints[i].description).openPopup();
					}
					
				}
			}}).get({'task':'mappoints','nw': bounds.getNorthWest(), 'se': bounds.getSouthEast()});
	});
	
	
	
	L.tileLayer('http://{s}.tile.cloudmade.com/9ad2029a7cff49ea8d3445b55352f445/997/256/{z}/{x}/{y}.png', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
		maxZoom: 18
	}).addTo(map);
		

	
		
});