<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * UKRGB map Points Model
*/
class UkrgbModelMapPoints extends JModelItem
{
	/**
	 * Get the basic Data
	 * @return returns an arry with the basic map data Lat, Long and Zoom
	 */
	public function getMapPoints()
	{
		$input = JFactory::getApplication()->input;
		$nwCorner = $input->get ('nw',null,'array');
		$seCorner = $input->get ('se',null,'array');
		$n_lat = doubleval($nwCorner['lat']);
		$w_lng = doubleval($nwCorner['lng']);
		$s_lat = doubleval($seCorner['lat']);
		$e_lng = doubleval($seCorner['lng']);
		
		//error_log('n '.$n_lat.', w '.$w_lng.', s '.$s_lat.', e '.$e_lng);
			
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
				$db->quoteName('id'),
				$db->quoteName('riverguide'),
				'X('.$db->quoteName('point').') AS X',
				'Y('.$db->quoteName('point').') AS Y',
				$db->quoteName('type'),
				$db->quoteName('description')));
		$query->from('#__ukrgb_map_point');
		$query->where('MBRContains(GeomFromText(\'Polygon(('.$n_lat.' '.$w_lng.', '.$n_lat.' '.$e_lng.', '.$s_lat.' '.$e_lng.', '.$s_lat.' '.$w_lng.', '.$n_lat.' '.$w_lng.'))\'),'.$db->quoteName('point').')');
		//error_log($query);
		$db->setQuery($query); 
		
		try {
			$result = $db->loadObjectList();
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}		
		return $result;
	}
}