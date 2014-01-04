<?php

// Admin - Map Model

class UkrgbModelMap extends JModelBase
{
	/**
	 * Get the Map parameters
	 * @return array - with the map prameters
	 * (w_lng, s_lat, E_lng n_lat and map_type)
	 * Map Type:
	 * 0 - everything
	 * 10 - retail outlets.
	 */
	public function getMapParameters($mapid)
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

}

