'''
Created on 10 Jan 2013

@author: mark.gawler
'''
import unittest
import gridref

class Test(unittest.TestCase):


    def testName(self):
        pass


    def testGridRef1(self):
        
        e,n = gridref.OSGRtoNE('SX586920')
        lat, lon   = gridref.OSGB36toWGS84(e,n)
        print lat,lon
      
    def testGridRef2(self):
        
        lat,lng = gridref.OSGRtoWGS84('SX 586 920')
        print lat,lng
        
    def testGridRef3(self):
        '''
        SE 60724 48627
        Latitude :        Longitude :
        53.930220         -1.0766602
        '''
        lat,lng = gridref.OSGRtoWGS84('SE 60724 48627')
        print lat,lng 
        
if __name__ == "__main__":
    #import sys;sys.argv = ['', 'Test.testName']
    unittest.main()