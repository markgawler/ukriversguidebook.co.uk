'''
Created on 26 Feb 2013

@author: mark.gawler
'''
import pyproj
import gridref
import string
from math import floor

wgs84=pyproj.Proj("+init=EPSG:4326") # LatLon with WGS84 datum 
osgb36=pyproj.Proj("+init=EPSG:27700") # UK Ordnance Survey, 1936 datum (OSGB36)

#e = -1.0766602
#n = 53.930220

e = 394556 
n = 808624 

lat,lng = pyproj.transform(osgb36,wgs84 , e, n)
print lat,lng 

lng,lat = gridref.OSGB36toWGS84(e,n)
print lat, lng


#http://www.bing.com/maps/default.aspx?sty=s&mkt=gb&lvl=14&cp=54.28488~-2.12285
# SD 921 877
lat = 54.28488 
lng = -2.12285

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

print "%s %0*d %0*d" % (prefix, rez, zone_east, rez, zone_north)
