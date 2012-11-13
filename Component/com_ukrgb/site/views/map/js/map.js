window.addEvent("domready", function() {
	 // Create a new map instance
		var map = L.map('map').setView([51.505, -0.09], 13);
		
		L.tileLayer('http://{s}.tile.cloudmade.com/9ad2029a7cff49ea8d3445b55352f445/997/256/{z}/{x}/{y}.png', {
		    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
		    maxZoom: 18
		}).addTo(map);

});