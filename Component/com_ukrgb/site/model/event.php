<?php

// Site - event  Model
defined('_JEXEC') or die;

class UkrgbModelEvent extends JModelDatabase
{
	public function store($ev,$id = null)
	{
		if (empty($id))
		{
			$result = $this->newEvent($ev);
		}
		else
		{
			$result = $this->updateEvent($ev, $id);
		}
		if (!empty($result)){
			// Sucsess clear for from userState
			$app =& JFactory::getApplication();
			$data = $app->setUserState( 'com_ukrgb.event.data', null);
		}
		return $result;
	}
	
	private function updateEvent ($ev,$id)
	{
		// Update an Event
		try {
			$ev->id = $id;
			$result = $this->db->updateObject('#__ukrgb_events', $ev, 'id');
		} catch (Exception $e) {
			// catch any database errors.
			$result = null;
		}
		return $result;
	}
	
	private function newEvent ($ev)
	{
		// Create a new event
	
		//$query = $this->db->getQuery(true);
		
		// Insert the object into the user profile table.
		try {
			$result = $this->db->insertObject('#__ukrgb_events', $ev);
			
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
		return $result;
		
	}
	
	public function load($id)
	{	
		// Create a new query object.
		$db = $this->db;
		$query = $db->getQuery(true);
	
		$query->select($db->quoteName(array(
				'id', 'created_by', 'calendar','location', 'title', 'start_date',
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
	
	public function getCreator($id)
	{
		// Get the event creator.
		$query = $this->db->getQuery(true);
	
		$query->select($this->db->quoteName(array('created_by')))
		->from($this->db->quoteName('#__ukrgb_events'))
		->where('id = '. $this->db->quote($id));
		
		 $this->db->setQuery($query);
		
		try {
			$result =  $this->db->loadResult();
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
			$result = null;
		}
		return $result; 
	}
	
}
