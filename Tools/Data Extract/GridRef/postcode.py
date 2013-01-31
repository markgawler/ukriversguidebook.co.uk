'''
Created on 30 Jan 2013

@author: mark.gawler
'''
import gridref
import glob
import csv

#DATAPATH = "D:\\snapshot\\MG_VS2010_6-1\\DataGeneration\\Datasets\\Inventory\\Data\\PostSFT\\Assemblies\\"
DATAPATH = "D:\\Local\\ukrgb\\maps\\PostCode\\Data\\"
       

print "Starting..."
for f in glob.glob(DATAPATH + '*.csv'):
    print f
    
    with open(f, 'rb') as f:
        reader = csv.reader(f)
        for row in reader:
            code = row[0]
            e = int(row[2])
            n = int(row[3])
            lat,lng = gridref.OSGB36toWGS84(e,n)
            print code,lat,lng
print "Done."

