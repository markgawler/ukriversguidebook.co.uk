<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * UKRGB RiverGuide Model
*/
class UkrgbModelRiverGuide extends JModelItem
{
	/**
	 * @var string msg
	 */
	//protected $msg;

	public function getTable($type = 'RiverGuide', $prefix = 'UkrgbTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Get the basic Data
	 * @return returns an arry with the basic map data Lat, Long and Zoom
	 */
	public function getGuideData()
	{
		// Get a Table instance
		$table = $this->getTable();
		//error_log($table);
		
		$db =  $table->getDbo();
		$query = "SELECT ".$db->nameQuote('article_id').", ".$db->nameQuote('map_id').", ".$db->nameQuote('grade')." FROM ".$db->nameQuote('#__ukrgb_riverguides').";";
		
		//error_log($query);
		
		$db->setQuery($query);
		$result = $db->loadRow();
				
		$data = array("article" => $result[0],
				"map" => $result[1],
				"grade" => $result[2],);
		return $data;
	}
}