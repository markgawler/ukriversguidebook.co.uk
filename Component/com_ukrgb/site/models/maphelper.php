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
	
}