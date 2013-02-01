<?php

// no direct access
defined('_JEXEC' ) or die('Restricted access' );

class UkrgbMapHelper
{
	/**
	 * Get the basic Data
	 * @return array - with the basic map data bounds and type array
	 * (w_Lat, s_Long, E_Lat n_long and map_type)
	 * Map Type:
	 * 0 - everything
	 * 10 - retail outlets.
	 */
	public function getBasicMapData($mapid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('X(sw_corner)', 'Y(sw_corner)', 'X(ne_corner)', 'Y(ne_corner)' ,'map_type'));
		$query->from('#__ukrgb_maps');
		$query->where('id = ' . $db->Quote($mapid));
		$db->setQuery($query);

		$result = $db->loadRow();

		$data = array("w_lat" => $result[0],
				"s_long" => $result[1],
				"e_lat" => $result[2],
				"n_long" => $result[3],
				"map_type" => $result[4],);
		return $data;
	}
	
}