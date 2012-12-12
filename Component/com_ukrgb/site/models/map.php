<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * UKRGB Map Model
*/
class UkrgbModelMap extends JModelItem
{
	/**
	 * Get the basic Data
	 * @return returns an arry with the basic map data Lat, Long and Zoom
	 */
	public function getBasicMapData()
	{
		//error_log("Map Model");
		
		// What map is being requested
		$input = JFactory::getApplication()->input;
		$mapid = $input->get ('mapid');
				
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

