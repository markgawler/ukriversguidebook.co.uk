<?php

// no direct access
defined('_JEXEC' ) or die('Restricted access' );
require_once '../lib/proj4php/proj4php.php';

class UkrgbMapPointsHelper
{
	
	public function updateMapPoints($text,$id)
	{
	/*
	 text =  article text
	 id = article id
	 */
		
		/*
		 * pg = re.compile(r'(?:[\s|\(|\[])'
		 		r'([STNOHJG][A-Z]\s?[0-9]{3,5}\s?[0-9]{3,5})'
		 		r'(?:[\s|\|\]|\.)])')
		*/
		
		$pat = 	"/([STNOH][A-HJ-Z]\s?[0-9]{3,5}\s?[0-9]{3,5})/";
		$res = preg_match_all ( $pat , $text, $matches);
		if ($res >0 && $res != False){
			//wgs84=pyproj.Proj("+init=EPSG:4326") # LatLon with WGS84 datum
			//osgb36=pyproj.Proj("+init=EPSG:27700") # UK Ordnance Survey, 1936 datum (OSGB36)
			// osgb36=pyproj.Proj("+proj=tmerc +lat_0=49 +lon_0=-2 +k=0.9996012717 +x_0=400000 +y_0=-100000 +ellps=airy +datum=OSGB36 +units=m +no_defs ") # UK Ordnance Survey, 1936 datum (OSGB36)
			
			$proj4 = new Proj4php();
			$projWGS84 = new Proj4phpProj('EPSG:4326',$proj4);
			//$projOSGB36 = new Proj4phpProj('EPSG:27700',$proj4);
			$projOSGB36 = new Proj4phpProj('+proj=tmerc +lat_0=49 +lon_0=-2 +k=0.9996012717 +x_0=400000 +y_0=-100000 +ellps=airy +datum=OSGB36 +units=m +no_defs',$proj4);
			// Gr found
			foreach ($matches[0] as $gr){
				$gr = str_replace(' ', '', $gr);
				$prefix = substr($gr,0,2);
				print $gr;
				print '    ';
				$en = UkrgbMapPointsHelper::OSGridtoNE($gr);
				var_dump($en);
				$pointSrc = new proj4phpPoint($en[0],$en[1]);
				var_dump($pointSrc);
				$pointDest = $proj4->transform($projOSGB36,$projWGS84,$pointSrc);
				var_dump($pointDest);
			}
		}
	}
		/*
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('X(sw_corner)', 'Y(sw_corner)', 'X(ne_corner)', 'Y(ne_corner)' ,'map_type', 'articleid'));
		$query->from('#__ukrgb_maps');
		$query->where('id = ' . $db->Quote($mapid));
		$db->setQuery($query);

		$result = $db->loadRow();

		$data = array("w_lng" => $result[0],
				"s_lat" => $result[1],
				"e_lng" => $result[2],
				"n_lat" => $result[3],
				"map_type" => $result[4],
				"aid" => $result[5]);
		return $data;
		*/
	//}
	/*
	public function getMapIdforArticle($articleid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id'));
		$query->from('#__ukrgb_maps');
		$query->where($db->quoteName('articleid') .' = '. $db->Quote($articleid));
		
		//error_log($query);
		$db->setQuery($query);
		try {
			$result = $db->loadObject();
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
		//ob_start();
		//var_dump($result);
		//error_log(ob_get_contents());
		//ob_end_clean();
		//error_log($result->id);
		
		

		return $result->id;
	}
	*/
	
	function OSGridZonetoNE ($ossquare)
	{
		/*
	    Convert an Ordinance Survey zone (2 letter code) to distances from the reference point.
	    */
		    		
		// find the 500km square
		
		$lookup = array(
			'S' => array(0,0),
			'T' => array(1,0),
			'N' => array(0,1),
			'O' => array(1,1),
			'H' => array(0,2)
		);
		
		$key = substr($ossquare,0,1);	 
		$offset = $lookup[$key];
		$easting = $offset[0] * 500;
		$northing = $offset[1] * 500;
		 
		// find the 100km offset & add
		$grid = "VWXYZQRSTULMNOPFGHJKABCDE";
	    $key = substr($ossquare,1,1);
		    
	    $posn = strpos($grid,$key);
	    //print ('key: '.$key);
	    //print ('pos: '.$posn);
		$easting += ($posn % 5) * 100;
		$northing += (int)($posn / 5) * 100;
		return (array($easting * 1000, $northing * 1000));
	
	}
	function OSGridtoNE($osgrStr)
	{
		$osgrStr = str_replace(' ', '', $osgrStr);
		
		$zone = substr($osgrStr,0,2); // Leading letters
		
		$coords = substr($osgrStr,2); // Numeric portion
		
		// reject odd number of digits
		//assert (len (coords) % 2 == 0),"'%s' must be an even number of digits" % coords
		
		// Calculate the size and resolution of numeric portion of the GR
		$rez = strlen($coords) / 2;
		$osgb_easting = substr($coords,0,$rez);
		$osgb_northing = substr($coords,$rez);
		
		//# what is each digit (in metres)
		$rez_unit = pow(10,5-$rez);
		//print "Rel: ".$rez_unit;
		$relEasting =   $osgb_easting * $rez_unit;
		$relNorthing =  $osgb_northing * $rez_unit;
		//print "east: " .$relEasting;
		//print "north: ".$relNorthing;
		
		$en =  UkrgbMapPointsHelper::OSGridZonetoNE($zone);
		$zoneEasting = $en[0];
		$zoneNorthing = $en[1];
		$e = $zoneEasting + $relEasting;
		$n = $zoneNorthing + $relNorthing;
		return array($e,$n);
	}
}