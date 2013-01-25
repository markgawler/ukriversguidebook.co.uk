'''
Created on 24 Jan 2013

@author: mark.gawler
'''

import gridref
import re 

def test():
    
    data = [" SS0123401234 ",
            "(SS123456)",
            " SS123456 ",
            " SS 123456 ",
            " SS 123 456 ",
            "(SS01234 01234)",
            " SS 0123401234",
            " SS 12 45",
            " SS012345 012345 ",
            " SS 012345 012345 ",
            " SS 1234 123a ",
            " SS1234 123a ",
            " SS1234123a ",
            " SS 123 123a ",
            " SS 123 123 a ",
            "(SS123466) fff (SS123499)",
            "ou'll find it behind Jewsons (GR 556374)! It's fed by tidal flow",
            "Access at Watersmeet (SS744486) is below "]
    
    p = re.compile(r'(?:[\s|\(])'
                   r'([STNOHJ][VWXYZQRSTULMNOPFGHJKABCDE]\s?[0-9]{3,5}\s?[0-9]{3,5})'
                   r'(?:[\s|\)])')
    
        
    for t in data: 
        print t

        for m in p.finditer(t):
            gr = m.group(1)
            lat,lng = gridref.OSGRtoWGS84(gr)
            print '    -',gr,lat,lng


if __name__ == '__main__':
    test()