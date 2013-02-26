'''
Created on 26 Feb 2013

@author: mark.gawler
'''


'''
Created on 10 Jan 2013

@author: mark.gawler

OSGR - eg SX586920, SX 5865 9207, etc.
OSGB36 - Northings and Eastings 
WGS84  - Northings and Eastings 

Inspired by: 
    http://hannahfry.co.uk/2012/02/01/converting-british-national-grid-to-latitude-and-longitude-ii/
    http://www.agapow.net/programming/python/converting-ordnance-survey-coordinates
    http://all-geo.org/volcan01010/2012/11/change-coordinates-with-pyproj/
'''
#rom math import sqrt, pi, sin, cos, tan, atan2 as arctan2
import pyproj
import string
from math import floor
    
def OSGridtoWGS84(gridRef):
    '''
        Convert an OS Grid ref eg. SX586920 to WGS84 lat, long.
    '''
    wgs84=pyproj.Proj("+init=EPSG:4326") # LatLon with WGS84 datum 
    osgb36=pyproj.Proj("+init=EPSG:27700") # UK Ordnance Survey, 1936 datum (OSGB36)
    e,n = OSGridtoNE(gridRef) # convert to nothings and eastings
    lat,lng = pyproj.transform(osgb36,wgs84 , e, n)
    #lat, lon   = OSGB36toWGS84(e,n)
    return lng,lat
        
def OSGridtoNE(osgrStr):
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
    

def OSGB36toWGS84(e,n):
    '''
        Wraper for compatibility with old version although parameter order may have switches :-(
    '''
    wgs84=pyproj.Proj("+init=EPSG:4326") # LatLon with WGS84 datum 
    osgb36=pyproj.Proj("+init=EPSG:27700") # UK Ordnance Survey, 1936 datum (OSGB36)
    lat,lng = pyproj.transform(osgb36,wgs84 , e, n)
    return lng,lat


def OSGB36toOSGrid(lng,lat):
    osgb36=pyproj.Proj("+init=EPSG:27700") # UK Ordnance Survey, 1936 datum (OSGB36)
    e,n = osgb36(lng,lat)
    e100k = int (floor (e / 100000))
    n100k = int (floor (n / 100000))
    
    # translate those into numeric equivalents of the grid letters
    majorsq = (19-n100k) - (19-n100k)%5 + int (floor((e100k+10)/5))
    minorsq = (19-n100k)*5%25 + e100k%5;
        
    # map to letters (note the missing I in the grid!)
    gridLetters = string.uppercase.replace ('I', '')
    prefix = gridLetters[majorsq] + gridLetters[minorsq]
    rez = 3 # six digit GR 
    # strip 100km-grid indices from easting & northing, and reduce precision
    zone_east = int (floor ((e % 100000) / 10**(5-rez))) 
    zone_north = int (floor ((n % 100000) / 10**(5-rez)))
    
    return "%s %0*d %0*d" % (prefix, rez, zone_east, rez, zone_north)



if __name__ == '__main__':
    pass