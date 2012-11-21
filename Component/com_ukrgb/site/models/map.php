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
	 * @var string msg
	 */
	//protected $msg;

	public function getTable($type = 'Map', $prefix = 'UkrgbTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Get the basic Data
	 * @return returns an arry with the basic map data Lat, Long and Zoom
	 */
	public function getBasicMapData()
	{
		// What map is being requested
		$input = JFactory::getApplication()->input;
		$mapid = $input->get ('mapid');
		
		
		//SELECT X(`centre_point`), Y(`centre_point`), `zoom` FROM `jos_ukrgb_maps` WHERE 1
		
		// Get a Table instance
		$table = $this->getTable();
		error_log($table);
		
		$db =  $table->getDbo();
		$query = "SELECT X(".$db->nameQuote('centre_point')."), Y(".$db->nameQuote('centre_point')."), ".$db->nameQuote('zoom')." FROM ".
			$db->nameQuote('#__ukrgb_maps')." WHERE ".$db->nameQuote('id')." = ".$mapid.";";
		error_log($query);
		
		$db->setQuery($query);
		$result = $db->loadRow();
				
		$data = array("lat" => $result[0],
				"long" => $result[1],
				"zoom" => $result[2],);
		return $data;
	}
}