<?php

// Site - event  Model
defined('_JEXEC') or die;

class UkrgbModelEvent extends JModelDatabase
{
	public function store($ev)
	{
		//error_log('-- Store event');
		// Create a new query object.
		$query = $this->db->getQuery(true);
		
		// Insert the object into the user profile table.
		try {
			$result = $this->db->insertObject('#__ukrgb_events', $ev);
			
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
		
		$app =& JFactory::getApplication();
		$data = $app->setUserState( 'com_ukrgb.event.data', null);
		
	}
	
	public function load($id)
	{	
		//error_log('-- Load event');
		// Create a new query object.
		$db = $this->getDb();
		$query = $db->getQuery(true);
	
		$query->select($db->quoteName(array(
				'created_by', 'calendar','location', 'title', 'start_date',
				'end_date', 'all_day', 'course_provider', 'summary', 'description')))
		->from($db->quoteName('#__ukrgb_events'))
		->where('id = '. $this->db->quote($id));
		
		$db->setQuery($query);
		
		try {
			$result = $db->loadObject();
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
		return $result;
	}
	
}
