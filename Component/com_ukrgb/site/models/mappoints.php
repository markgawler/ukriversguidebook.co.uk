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
		error_log("getMapPoints");
		$input = JFactory::getApplication()->input;
		$nwCorner = $input->get ('nw',null,'array');
		$seCorner = $input->get ('se',null,'array');
		$guideId = $input->get ('guideid',null);
		if (isset($nwCorner) && isset($seCorner)) 
		{
			return UkrgbModelMapPoints::getByArea(
					doubleval($nwCorner['lat']),
					doubleval($nwCorner['lng']),
					doubleval($seCorner['lat']),
					doubleval($seCorner['lng']));
		} 
		elseif (isset($guideId))
		{
			return UkrgbModelMapPoints::getByGuideId($guideId);
		}
		else
		{
			error_log("Update?");
			return null;
		}
	}
	
	public function setMapPoint()
	{
		//"""INSERT INTO `jos_ukrgb_maps` (centre_point, zoom) VALUES( GeomFromText( 'POINT("""+str(x)+" "+str(y)+""")' ), 15);""")
		
		error_log("Set Map Point");		
		$input = JFactory::getApplication()->input;
		$riverguide = $input->get ('guideid');
		$latlng = $input->get ('latlng',null,'array');
		$label = $input->get ('label','','string');
		$type = $input->get ('type');
		$id = $input->get ('id');
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
	
		// Fields to update.
		$fields = array(
				$db->quoteName('riverguide').' = '.$db->quote($riverguide),
				$db->quoteName('point').' = GeomFromText('.$db->quote('POINT('.$latlng['lat'].' '.$latlng['lng'].')').')',
				$db->quoteName('type').' = '.$db->quote($type),
				$db->quoteName('description').' = '.$db->quote($label));
		
		$query->update($db->quoteName('#__ukrgb_map_point'))->set($fields)->where('id = '.$id);
		$db->setQuery($query);
		
		try {
			$result = $db->query(); 
		} catch (Exception $e) {
			error_log($e);
		}		
	}
		
	private function getByArea($n_lat, $w_lng, $s_lat, $e_lng)
	{
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
		$query->where('MBRContains(GeomFromText('.$db->quote('Polygon(('.$n_lat.' '.$w_lng.', '.$n_lat.' '.$e_lng.', '.$s_lat.' '.$e_lng.', '.$s_lat.' '.$w_lng.', '.$n_lat.' '.$w_lng.'))').'),'.$db->quoteName('point').')');
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
	
	private function getByGuideId($guideId)
	{
		error_log("getMapPoints - Guide: " . $guideId);
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
		$query->where('riverguide = '. $db->Quote($guideId));
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