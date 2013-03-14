<?php

// no direct access
defined('_JEXEC' ) or die('Restricted access' );

require_once JPATH_SITE . '/components/com_ukrgb/lib/proj4php/proj4php.php';

class UkrgbMapPointsHelper
{
	//TODO: update to use proj4phpPoint  object instead of using an array of points.
	
	public function updateMapPoints($text,$articleId,$description)
	{
	/**
	 text =  article text
	 id = article id
	 **/
		$pat = 	"/([STNOH][A-HJ-Z]\s?[0-9]{3,5}\s?[0-9]{3,5})/";
		$res = preg_match_all ( $pat , $text, $matches);
		if ($res >0 && $res != False){				
			
			$proj4 = new Proj4php();
			$projWGS84 = new Proj4phpProj('EPSG:4326',$proj4);	# LatLon with WGS84 datum
			$projOSGB36 = new Proj4phpProj('EPSG:27700',$proj4);# UK Ordnance Survey, 1936 datum (OSGB36)
			// remove existing points
			UkrgbMapPointsHelper::deleteMapPointsForArticle($articleId);
			$north = 0;
			$south = 2000;
			$east = 0;
			$west = 2000;
			$grSet = array(); // Array used as a set to ensure the GR is processed only once. 
			foreach ($matches[0] as $gr){
				$gr = str_replace(' ', '', $gr);
				if (!in_array($grSet,$gr)){
					$grSet[] = $gr; 
					error_log($gr);  //TODO: Don't process the grid ref. if it is repeated in the guide, currently two points get chr
					$prefix = substr($gr,0,2);
					($gr."   -  ");  //TODO: what's this doing here?
					$en = UkrgbMapPointsHelper::OSGridtoNE($gr);
					$pointSrc = new proj4phpPoint($en['x'],$en['y']);
					
					// Calculate the extent of the map.
					$north = max($north,$en['y']);
					$south = min($south,$en['y']);
					$east = max($east,$en['x']);
					$west = min($west,$en['x']);
					
					$pointDest = $proj4->transform($projOSGB36,$projWGS84,$pointSrc);
					UkrgbMapPointsHelper::addMapPoint($pointDest, $articleId, 0 ,$description);
				}
			}
			
			$swSrc = new proj4phpPoint($west,$north);
			$neSrc = new proj4phpPoint($east,$south);
			$swDest = $proj4->transform($projOSGB36,$projWGS84,$swSrc);
			$neDest = $proj4->transform($projOSGB36,$projWGS84,$neSrc);
			
			if (UkrgbMapHelper::getMapIdforArticle($articleId) == null)
			{
				ukrgbMapHelper::addMap(0,$swDest,$neDest,$articleId);
			} else {
				//TODO: Update the map area as it may have changed - no tested
				ukrgbMapHelper::updateMap(0,$swDest,$neDest,$articleId);
			}
		}
	}
	
	function addMapPoint ($point,$riverguide,$type,$description){
		/*
		 * */	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		 
		// Insert columns.
		$columns = array('riverguide', 'point', 'type', 'description');
		
		// Insert values.
		$values = array(
				$db->quote($riverguide),
				'GeomFromText('.$db->quote('POINT('.$point->x.' '.$point->y.')').')',
				$db->quote($type),
				$db->quote($description));
		
		// Prepare the insert query.
		$query->insert($db->quoteName('#__ukrgb_map_point'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		// Reset the query using our newly populated query object.
		
		$db->setQuery($query);
		
		try {
			$result = $db->query();
		} catch (Exception $e) {
			error_log($e);
		}
	}
	
	function deleteMapPointsForArticle ($articleId){
		/* Delete all the Map Points for the specified Article
		 * 
		 */
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__ukrgb_map_point'));
		$query->where('riverguide = '. $db->Quote($articleId));
		$db->setQuery($query);
		
		try {
			$result = $db->query(); // $db->execute(); for Joomla 3.0.
		} catch (Exception $e) {
			error_log($e);
		}
	}
		
	
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
		$easting += ($posn % 5) * 100;
		$northing += (int)($posn / 5) * 100;
		return (array('x' => $easting * 1000, 'y' => $northing * 1000));
	
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
		$relEasting =   $osgb_easting * $rez_unit;
		$relNorthing =  $osgb_northing * $rez_unit;
		
		$en =  UkrgbMapPointsHelper::OSGridZonetoNE($zone);
		$zoneEasting = $en['x'];
		$zoneNorthing = $en['y'];

		return array(
				'x'=>$zoneEasting + $relEasting,
				'y'=>$zoneNorthing + $relNorthing);
	}
}