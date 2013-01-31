<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
require_once JPATH_SITE . DS . 'components' . DS . 'com_ukrgb' . DS . 'models' . DS . 'maphelper.php';

/**
 * UKRGB Map Model
*/
class UkrgbModelMap extends JModelItem
{
	/**
	 * Get the basic Data
	 * @return returns an aray with the basic map data Lat, Long and Zoom
	 */
	public function getBasicMapData()
	{
		// What map is being requested
		
		$input = JFactory::getApplication()->input;
		$mapid = $input->get ('mapid');
		
		return UkrgbMapHelper::getBasicMapData($mapid);
	}
}

