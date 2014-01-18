<?php defined( '_JEXEC' ) or die( 'Restricted access' );

// Site - Map Model

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

	public function getMapIdforArticle($articleid)
	{
		/*
		 * Get the Map ID for the article, null is returned if no map found
		* */
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('id'));
		$query->from('#__ukrgb_maps');
		$query->where($db->quoteName('articleid') .' = '. $db->Quote($articleid));
	
		$db->setQuery($query);
		try {
			$result = $db->loadObject();
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
	
		if ($result == null){
			return null; //No Map
		}
		return $result->id;
	}
	
	public function addMap($type, $sw ,$ne , $articleId)
	{
		/*
		 * Add A Map
		*/
	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
			
		// Insert columns.
		$columns = array('map_type', 'sw_corner', 'ne_corner', 'articleid');
	
		// Insert values.
		$values = array(
				$db->quote($type),
				'GeomFromText('.$db->quote('POINT('.$sw->x.' '.$sw->y.')').')',
				'GeomFromText('.$db->quote('POINT('.$ne->x.' '.$ne->y.')').')',
				$db->quote($articleId));
	
		// Prepare the insert query.
		$query->insert($db->quoteName('#__ukrgb_maps'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		// Reset the query using our newly populated query object.
	
		$db->setQuery($query);
		try {
			$result = $db->query();
		} catch (Exception $e) {
			error_log($e);
		}
	}
	
	public function updateMap($type, $sw ,$ne , $articleId)
	{
		/*
		 * Update A Map
		*/
		//TODO: Do we need to check the map exists?
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
			
		// Insert values.
		$fields = array(
				$db->quoteName('map_type').' = '.$db->quote($type),
				$db->quoteName('sw_corner').' = '.'GeomFromText('.$db->quote('POINT('.$sw->x.' '.$sw->y.')').')',
				$db->quoteName('ne_corner').' = '.'GeomFromText('.$db->quote('POINT('.$ne->x.' '.$ne->y.')').')');
	
		// Prepare the insert query.
		$query->update($db->quoteName('#__ukrgb_maps'))->set($fields)->where('articleid = '.$articleId);
		$db->setQuery($query);
		try {
			$result = $db->query();
		} catch (Exception $e) {
			error_log($e);
		}
	}
	
}

