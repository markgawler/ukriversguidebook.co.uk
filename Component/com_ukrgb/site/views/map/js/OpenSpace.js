/**
 * @requires OpenLayers/Layer/WMS.js
 * @requires OpenLayers/Projection.js
 *
 * Class: OpenLayers.Layer.OpenSpace
 *
 * Inherits from:
 *  - <OpenLayers.Layer.WMS>
 */
OpenLayers.Layer.OpenSpace = OpenLayers.Class(OpenLayers.Layer.WMS, {
    /**
     * Constructor: OpenLayers.Layer.OpenSpace
     *
     * Parameters:
     * name - {String}
     * apikey - {String} Ordnance Survey OpenSpace API Key 
     * options - {Object} Hashtable of extra options to tag onto the layer
     * 
     * Stolen from:
     * http://trac.osgeo.org/openlayers/browser/sandbox/edgemaster/openlayers/lib/OpenLayers/Layer/OpenSpace.js
     */
    initialize: function(name, apikey, options) {
        options = OpenLayers.Util.extend({
            attribution: "&copy; Crown Copyright and database right 2012. All rights reserved. <a target='_blank' href='http://openspace.ordnancesurvey.co.uk/openspace/developeragreement.html#enduserlicense'>End User License Agreement</a>",
            maxExtent: new OpenLayers.Bounds(0, 0, 800000, 1300000),
            resolutions: [2500, 1000, 500, 200, 100, 50, 25, 10, 5, 2, 1],
            units: "m",
            projection: new OpenLayers.Projection("EPSG:27700")
        }, options);
        
        params = {
            "KEY": apikey
        };
        
        url = "http://openspace.ordnancesurvey.co.uk/osmapapi/ts";
        var newArguments = [name, url, params, options];
        OpenLayers.Layer.WMS.prototype.initialize.apply(this, newArguments);
    },
        
    /** 
     * Method: getFullRequestString
     * Combine the layer's url with its params and these newParams.
     * We add the OS API key to the request string
     *
     * Parameters:
     * newParams - {Object}
     * altUrl - {String} Use this as the url instead of the layer's url
     * 
     * Returns:
     * {String} 
     */
    getFullRequestString: function(newParams, altUrl) {
        this.params.LAYERS = this.resolutions[this.map.getZoom()];

        return OpenLayers.Layer.WMS.prototype.getFullRequestString.apply(
                                                    this, arguments);
    },
    
    /**
     * Method: initGriddedTiles
     * 
     * Parameters:
     * bounds - {<OpenLayers.Bounds>}
     */
    initGriddedTiles: function(bounds) {
        // The OS make it difficult for us, the highest resolution tiles are
        // served as 250x250px images, rather than the 200x200px as the rest.
        // OpenLayers.Layer.Grid calls this function if the zoom level changes
        
        res = this.map.getResolution();
        if(res > 2) {
            tileSize = new OpenLayers.Size(200,200);
        } else {
            tileSize = new OpenLayers.Size(250,250);
        }
        
        // If we do actually change tile size, we need to clear the grid
        if(!tileSize.equals(this.tileSize)) {
            this.tileSize = tileSize;
            this.clearGrid();
        }
        
        // Pass onto the grid class to actually recreate the grid
        OpenLayers.Layer.WMS.prototype.initGriddedTiles.apply(this, [bounds]);
    },

    CLASS_NAME: "OpenLayers.Layer.OpenSpace"
});


