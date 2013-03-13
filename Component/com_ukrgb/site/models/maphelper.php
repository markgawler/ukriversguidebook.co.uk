<?php

// no direct access
defined('_JEXEC' ) or die('Restricted access' );

class UkrgbMapHelper
{
	/**
	 * Get the basic Data
	 * @return array - with the basic map data bounds and type array
	 * (w_lng, s_lat, E_lng n_lat and map_type)
	 * Map Type:
	 * 0 - everything
	 * 10 - retail outlets.
	 */
	public function getBasicMapData($mapid)
	{
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
	}
	
	public function getMapIdforArticle($articleid)
	{
		/* 
		 * Get the Map ID for the article, null is returned if no map found 
		 * */
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id'));
		$query->from('#__ukrgb_maps');
		$query->where($db->quoteName('articleid') .' = '. $db->Quote($articleid));
		
		$db->setQuery($query);
		try {
			$result = $db->loadObject();
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
		
		if ($result == null){						
			return null; //No Map
		}
		return $result->id;
	}
	
	public function addMap($type, $sw ,$ne , $articleId)
	{
		/*
		 * Add A Map 
		 * */
		/*sql = """INSERT INTO `jos_ukrgb_maps` (`articleid`, `sw_corner`, `ne_corner`, `map_type`) VALUES (""" \
		+str(aid)+""", """+\
		"""GeomFromText( 'POINT(""" +str(lng1)+" " +str(lat1)+""")' ),"""+\
		"""GeomFromText( 'POINT(""" +str(lng2)+" " +str(lat2)+""")' ),0);"""
		*/
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
			
		// Insert columns.
		$columns = array('map_type', 'sw_corner', 'ne_corner', 'articleid');
		
		// Insert values.
		$values = array(
				$db->quote($type),
				'GeomFromText('.$db->quote('POINT('.$sw->x.' '.$sw->y.')').')',
				'GeomFromText('.$db->quote('POINT('.$ne->x.' '.$ne->y.')').')',
				$db->quote($articleId));
		
		// Prepare the insert query.
		$query->insert($db->quoteName('#__ukrgb_maps'))
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
}