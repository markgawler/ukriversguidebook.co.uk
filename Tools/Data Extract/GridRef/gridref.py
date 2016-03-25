'''
Created on 10 Jan 2013

@author: mark.gawler

OSGR - eg SX586920, SX 5865 9207, etc.
OSGB36 - Northings and Eastings 
WGS84  - Northings and Eastings 

Inspired by: 
    http://hannahfry.co.uk/2012/02/01/converting-british-national-grid-to-latitude-and-longitude-ii/
    and:
    http://www.agapow.net/programming/python/converting-ordnance-survey-coordinates
'''
from bng_to_latlon import OSGB36toWGS84  # pip install bng_latlon

    
def OSGRtoWGS84(gridRef):
    '''
        Convert an OS Grid ref eg. SX586920 to WGS84 lat, long.
    '''
    e,n = OSGRtoNE(gridRef)
    lat, lon   = OSGB36toWGS84(e,n)
    return lat,lon
        
def OSGRtoNE(osgrStr):
    #TODO: - confusing name return parameter order
    osgrStr = osgrStr.replace (' ', '').upper()
    
    zone = osgrStr[:2] # Leading letters
    coords = osgrStr[2:] # Numeric portion
    
    # reject odd number of digits
    assert (len (coords) % 2 == 0),"'%s' must be an even number of digits" % coords
    
    # Calculate the size and resolution of numeric portion of the GR
    rez = len (coords) / 2
    osgb_easting = coords[:rez]
    osgb_northing = coords[rez:]
    
    # what is each digit (in metres)
    rez_unit = 10.0**(5-rez)
    relEasting =  int (osgb_easting) * rez_unit
    relNorthing = int (osgb_northing) * rez_unit
    
    zoneEasting, zoneNorthing =  OSGridZonetoNE(zone)
    e = zoneEasting + relEasting
    n = zoneNorthing + relNorthing
    return e,n


def OSGridZonetoNE (ossquare):
    """
    Convert an Ordinance Survey zone (2 letter code) to distances from the reference point.
    
    """
    ## Preconditions:
    assert (len (ossquare) == 2)
    ## Main:
    # find the 500km square
    lookup = {'S':[0,0],
              'T':[1,0],
              'N':[0,1],
              'O':[1,1],
              'H':[0,2],
              'J':[1,2]}
    key = ossquare[0]
    assert (key in lookup),"'%s' is not an OSGB 500km square (1st char)" % key
     
    offset = lookup[key]
    easting = offset[0] * 500
    northing = offset[1] * 500
     
    # find the 100km offset & add
    grid = "VWXYZQRSTULMNOPFGHJKABCDE"
    key = ossquare[1]
    assert (key in grid), "'%s' is not an OSGB 100km square (2nd char)" % key
    
    posn = grid.find (key)
    easting += (posn % 5) * 100
    northing += (posn / 5) * 100
    return easting * 1000, northing * 1000
