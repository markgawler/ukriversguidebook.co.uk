'''
Created on 24 Jan 2013

@author: mark.gawler
'''
import unittest
import osgb

class Test(unittest.TestCase):


    def testOSGB(self):
        lon, lat  = osgb.osgb_to_lonlat('SX586920')
        print lat,lon
        
    

if __name__ == "__main__":
    #import sys;sys.argv = ['', 'Test.testGR1']
    unittest.main()