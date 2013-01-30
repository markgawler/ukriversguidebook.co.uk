<?php

// no direct access
defined('_JEXEC' ) or die('Restricted access' );

class UkrgbMapHelper
{
	/**
	 * Get the basic Data
	 * @return returns an arry with the basic map data Lat, Long and Zoom
	 */
	public function getBasicMapData($mapid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('X(centre_point)', 'Y(centre_point)', 'zoom'));
		$query->from('#__ukrgb_maps');
		$query->where('id = ' . $db->Quote($mapid));
		$db->setQuery($query);

		$result = $db->loadRow();

		$data = array("lat" => $result[0],
				"long" => $result[1],
				"zoom" => $result[2],);
		return $data;
	}
	
}