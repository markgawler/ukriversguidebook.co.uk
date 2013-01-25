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
from math import sqrt, pi, sin, cos, tan, atan2 as arctan2

    
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
    

def OSGB36toWGS84(E,N):
    '''
    Convert British national grid coordinates (eastings and northings) to WGS84
    Credit to: 
        HANNAH FRY 
        http://hannahfry.co.uk/author/hannahfry/
        http://hannahfry.co.uk/2012/02/01/converting-british-national-grid-to-latitude-and-longitude-ii/
    '''
    #E, N are the British national grid coordinates - eastings and northings
    a, b = 6377563.396, 6356256.909     #The Airy 180 semi-major and semi-minor axes used for OSGB36 (m)
    F0 = 0.9996012717                   #scale factor on the central meridian
    lat0 = 49*pi/180                    #Latitude of true origin (radians)
    lon0 = -2*pi/180                    #Longtitude of true origin and central meridian (radians)
    N0, E0 = -100000, 400000            #Northing & easting of true origin (m)
    e2 = 1 - (b*b)/(a*a)                #eccentricity squared
    n = (a-b)/(a+b)

    #Initialise the iterative variables
    lat,M = lat0, 0

    while N-N0-M >= 0.00001: #Accurate to 0.01mm
        lat = (N-N0-M)/(a*F0) + lat;
        M1 = (1 + n + (5/4)*n**2 + (5/4)*n**3) * (lat-lat0)
        M2 = (3*n + 3*n**2 + (21/8)*n**3) * sin(lat-lat0) * cos(lat+lat0)
        M3 = ((15/8)*n**2 + (15/8)*n**3) * sin(2*(lat-lat0)) * cos(2*(lat+lat0))
        M4 = (35/24)*n**3 * sin(3*(lat-lat0)) * cos(3*(lat+lat0))
        #meridional arc
        M = b * F0 * (M1 - M2 + M3 - M4)          

    #transverse radius of curvature
    nu = a*F0/sqrt(1-e2*sin(lat)**2)

    #meridional radius of curvature
    rho = a*F0*(1-e2)*(1-e2*sin(lat)**2)**(-1.5)
    eta2 = nu/rho-1

    secLat = 1./cos(lat)
    VII = tan(lat)/(2*rho*nu)
    VIII = tan(lat)/(24*rho*nu**3)*(5+3*tan(lat)**2+eta2-9*tan(lat)**2*eta2)
    IX = tan(lat)/(720*rho*nu**5)*(61+90*tan(lat)**2+45*tan(lat)**4)
    X = secLat/nu
    XI = secLat/(6*nu**3)*(nu/rho+2*tan(lat)**2)
    XII = secLat/(120*nu**5)*(5+28*tan(lat)**2+24*tan(lat)**4)
    XIIA = secLat/(5040*nu**7)*(61+662*tan(lat)**2+1320*tan(lat)**4+720*tan(lat)**6)
    dE = E-E0

    #These are on the wrong ellipsoid currently: Airy1830. (Denoted by _1)
    lat_1 = lat - VII*dE**2 + VIII*dE**4 - IX*dE**6
    lon_1 = lon0 + X*dE - XI*dE**3 + XII*dE**5 - XIIA*dE**7

    #Want to convert to the GRS80 ellipsoid. 
    #First convert to cartesian from spherical polar coordinates
    H = 0 #Third spherical coord. 
    x_1 = (nu/F0 + H)*cos(lat_1)*cos(lon_1)
    y_1 = (nu/F0+ H)*cos(lat_1)*sin(lon_1)
    z_1 = ((1-e2)*nu/F0 +H)*sin(lat_1)

    #Perform Helmut transform (to go between Airy 1830 (_1) and GRS80 (_2))
    s = -20.4894*10**-6 #The scale factor -1
    tx, ty, tz = 446.448, -125.157, + 542.060 #The translations along x,y,z axes respectively
    rxs,rys,rzs = 0.1502,  0.2470,  0.8421  #The rotations along x,y,z respectively, in seconds
    rx, ry, rz = rxs*pi/(180*3600.), rys*pi/(180*3600.), rzs*pi/(180*3600.) #In radians
    x_2 = tx + (1+s)*x_1 + (-rz)*y_1 + (ry)*z_1
    y_2 = ty + (rz)*x_1  + (1+s)*y_1 + (-rx)*z_1
    z_2 = tz + (-ry)*x_1 + (rx)*y_1 +  (1+s)*z_1

    #Back to spherical polar coordinates from cartesian
    #Need some of the characteristics of the new ellipsoid    
    a_2, b_2 =6378137.000, 6356752.3141 #The GSR80 semi-major and semi-minor axes used for WGS84(m)
    e2_2 = 1- (b_2*b_2)/(a_2*a_2)   #The eccentricity of the GRS80 ellipsoid
    p = sqrt(x_2**2 + y_2**2)

    #Lat is obtained by an iterative proceedure:   
    lat = arctan2(z_2,(p*(1-e2_2))) #Initial value
    latold = 2*pi
    while abs(lat - latold)>10**-16: 
        lat, latold = latold, lat
        nu_2 = a_2/sqrt(1-e2_2*sin(latold)**2)
        lat = arctan2(z_2+e2_2*nu_2*sin(latold), p)

    #Lon and height are then pretty easy
    lon = arctan2(y_2,x_2)
    H = p/cos(lat) - nu_2

    #Uncomment this line if you want to print the results
    #print [(lat-lat_1)*180/pi, (lon - lon_1)*180/pi]

    #Convert to degrees
    lat = lat*180/pi
    lon = lon*180/pi

    #Job's a good'n. 
    return lat, lon





if __name__ == '__main__':
    pass