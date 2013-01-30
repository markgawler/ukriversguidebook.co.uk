var markers_group = {};
var mappoints = new Array();
var map = {};

var mapPointTypes = {putIn:1, takeOut:2, alternate:3}; 

window.addEvent("domready", function() {
	// Create a new map instance
	map = L.map('map');
	var id = 0; //TODO - ids for new markers
	mapData = params.mapdata;
	map.setView([mapData.lat, mapData.long], mapData.zoom);
	markers_group = L.layerGroup().addTo(map);
	
	// init load
	var r = new Request.JSON({url: params.url,
		onSuccess: function(mapPoints){
			// mapPoints processing
			for (var i = 0; i < mapPoints.length; i++){
				id = id +1;
				addMarker(id,([mapPoints[i].X, mapPoints[i].Y]),mapPoints[i].id,mapPoints[i].description,mapPoints[i].type,false);		
				//console.log(mapPoints[i].type);
			}
		}}).get({'task':'mappoints','guideid': params.guideid});
	
	map.on('click', function(e) {	
		id = id +1;
		console.log("New Maker: " + id);
		addMarker(id,e.latlng,0,'',mapPointTypes.alternate,true);//DBid=0 new point
	});
	
	//map.on('popupclose', function(e) {
	//	console.log('Popup Close: ' + id)
	//});
	
	L.tileLayer('http://{s}.tile.cloudmade.com/9ad2029a7cff49ea8d3445b55352f445/997/256/{z}/{x}/{y}.png', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery Â© <a href="http://cloudmade.com">CloudMade</a>',
		maxZoom: 18
	}).addTo(map);
	
});

// Add marker
function addMarker(id,latlng,dbid,label,type,isNew){
	marker = L.marker(latlng,{draggable : true}).addTo(markers_group);
	marker.myid = id;
	mappoints[id]= {
			dbid : dbid,
			latlng : latlng,
			label: label, 
			type: type};
	
	console.log("dbid: ",dbid);

	var form =  
		'<form id="popupForm" >'+
		'<input type="hidden" name="id" value="'+marker.myid+'">' +
		'<input type="hidden" name="isNew" value="'+isNew+'">' +
		'Label: <input type="text" name="popupLabel">'+
		  '<br>'+
          '<input type="radio" name="pointType" value="'+mapPointTypes.putIn+'">Put-In'+
          '<br>'+
          '<input type="radio" name="pointType" value="'+mapPointTypes.takeOut+'">Take-Out'+
          '<br>'+
          '<input type="radio" name="pointType" value="'+mapPointTypes.alternate+'" checked>Alternate Put-in/ Take-out'+
          '<br>'+
          '<div style="text-align:center;">'+
        	'<button type="button" onclick="popupSubmit()">OK</button>'+
        	'<button id = "deleteButton" type="button" onclick="deletePoint()">Delete</button>'+
        	'<button type="button" onclick="popupCancel()">Cancel</button>'+
          '</div>' +
          '</form>';
	
	if (isNew){
		marker.bindPopup(form).openPopup();
		$('deleteButton').setStyle('display', 'none'); 
	}else{
		marker.bindPopup(form);
	}
	
	marker.on('dragend', function(e) {
		var mp = mappoints[this.myid];
		var ll = e.target.getLatLng();
		e.target.openPopup();
		console.log("draged" + ll);
		populateForm(mp);
	});
	
	marker.on('click',function(e){
		var mp = mappoints[this.myid];
		if (mp != null){ 
			populateForm(mp);
		}
	});
			
	function populateForm(mp){
		$('deleteButton').setStyle('display', 'inline'); 

		var f = document.forms["popupForm"];
		f["popupLabel"].value = mp.label;
		f["isNew"].value = false; 

		switch(Number(mp.type)){
		case mapPointTypes.putIn:
			f["pointType"][0].checked = true;
			break;
		case mapPointTypes.takeOut:
			f["pointType"][1].checked = true;
			break;dragging
		default:
			f["pointType"][2].checked = true;
		}
	};
};

//  Popup Form submit action
function popupSubmit(){

	var f=document.forms["popupForm"];
	var l= f["popupLabel"].value;
	var formid = f["id"].value;
	var t = f["pointType"];
	var v = 0;
	for (var c=0; c<t.length; c++){
		if (t[c].checked){v = t[c].value;};
	};

	console.log('Submit Form id:',formid);

	
	//extract lat long from map the point on save.
	var latlng = getMarkerById(formid).getLatLng();
	mappoints[formid].latlng = latlng;
	mappoints[formid].label = l;
	mappoints[formid].type = v;
	
	var myRequest = new Request({
	    url: 'http://ukrgb-beta.homedomain/index.php?option=com_ukrgb&task=mappointstore'/*,
	    onSuccess: function(s){
	    	alert("Done");
	    }*/
	}).send(Object.toQueryString({
		guideid: params.guideid,
		latlng: latlng,
		label: l,
		type: v,
		id: mappoints[formid].dbid}
	));
	
	map.closePopup();

	return false;
}


function deletePoint(){
	var id=document.forms["popupForm"]["id"].value;
	markers_group.removeLayer(getMarkerById(id));
};

// Cancel the popup. New markers are deleted otherwise the marker is returned to its original state
function popupCancel(){
	if (document.forms["popupForm"]["isNew"].value == 'true') {
		deletePoint();
	}else{
		var id = document.forms["popupForm"]["id"].value;
		getMarkerById(id).setLatLng(mappoints[id].latlng);
		map.closePopup();
	}
	
}
// Find the marker on the Map with a given ID
function getMarkerById(id){
	var mkr = null;
	markers_group.eachLayer(function (layer) {
		if (layer.myid == id){
			console.log("Marker Found: "+id);
			mkr = layer;
	    }
	});
	if (mkr == null){console.log("Marker Not Found");}
	return mkr;
}

function submitMapAction(){
	console.log('Submit action.');
	

	console.log(queryString);
	var r = new Request({url: 'http://ukrgb-beta.homedomain/index.php?option=com_ukrgb&task=map',
		onSuccess: function(){
			alert("Done");
		}}).post();
};
