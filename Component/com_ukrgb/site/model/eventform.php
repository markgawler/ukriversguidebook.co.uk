<?php
// Site - event form
defined('_JEXEC') or die;
	
class UkrgbModelEventform
{
	/**
	 * Method to get the form object.
	 *
	 * @param	object  $ev	An optional argument, event object to populated the form with.
	 *
	 * @return  mixed  JForm 	object on success, False on error.
	 *
	 */
	public function getForm($ev = null)
	{
		if (!empty($ev))
		{
			$data = $this->eventObjectToFormArray($ev);			
		}
		else 
		{
			$data = array();
		}
		
		$app = JFactory::getApplication();
		
		// If data is empty set load_data to load data from userState
		$form = $this->loadForm('com_ukrgb.event', 'event',
				array('control' => 'jform', 'load_data' => empty($data)));

		if (!empty($form))
		{
			// Load the data into the form
			$form->bind($data);
		}
		else
		{
			return false;
		}
		return $form;
	}
	
	/**
	 * Method to get a form object.
	 *
	 * @param   string   $name     The name of the form.
	 * @param   string   $source   The form source. Can be XML string if file flag is set to false.
	 * @param   array    $options  Optional array of options for the form creation.
	 * @param   boolean  $clear    Optional argument to force load a new form.
	 * @param   string   $xpath    An optional xpath to search for the fields.
	 *
	 * @return  mixed  JForm object on success, False on error.
	 *
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);
	
		// Create a signature hash.
		$hash = sha1($source . serialize($options));
	
		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{	//@TODO I dont think form caching is working
			return $this->_forms[$hash];
		}
	
		// Get the form.
		// Register the paths for the form -- failing here
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/model/form', 'normal');
	
		// Solution until JForm supports splqueue
		JForm::addFormPath(JPATH_COMPONENT . '/model/form');
	
		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);
			
			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}
			
			// Load the data into the form after the plugins have operated.
			$form->bind($data);
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage());
	
			return false;
		}
	
		// Store the form for later.
		$this->_forms[$hash] = $form;
			
		return $form;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 *
	 */
	public function validate($form, $data, $group = null)
	{
		// Filter and validate the form data.
		$data   = $form->filter($data);
		$return = $form->validate($data, $group);
	
		// Check for an error.
		if ($return instanceof Exception)
		{
			JFactory::getApplication()->enqueueMessage($return->getMessage(), 'error');
	
			return false;
		}
	
		// Check the validation results.
		if ($return === false)
		{
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message)
			{
				JFactory::getApplication()->enqueueMessage($message, 'error');
			}
			return false;
		}
	
		//validate times
		if (!$this->validateDateTime($data['eventStart']))
		{
			JFactory::getApplication()->enqueueMessage('Event Start date is not valid', 'error');
			return false;
		}
		if (!$this->validateDateTime($data['eventEnd']) && $data['eventEnd'] != '')
		{
			JFactory::getApplication()->enqueueMessage('Event End date is not valid', 'error');
			return false;
		}
		return $data;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 */
	protected function loadFormData()
	{
		$app =& JFactory::getApplication();
		$data = $app->getUserState( 'com_ukrgb.event.data', null );
	
		if ($data != null)
		{
			// load event data from the user state as event is still beaing created.
			$data['eventStart'] = ''; //@TODO Bug in joomla causing dates to be populated incorectly?
			$data['eventEnd'] = '';   //@TODO http://forum.joomla.org/viewtopic.php?f=727&t=826469
			return $data;
		}
		
		return array();
	}
	
	private function validateDateTime($dateString)
	{
		$phptime = DateTime::createFromFormat('d-m-Y', $dateString);
	
		if ($phptime)
		{
			return true;
		}
		return false;
	}
	
	private function sqlDateTime($dateString)
	{
		$phptime = DateTime::createFromFormat('d-m-Y', $dateString);
		if ($phptime)
		{
			$phptime->setTime(0,0); //set hours an minutes
			$result = $phptime->format("Y-m-d H:i:s");
			return $result;
		}
		return $phptime;
	}
	
	
	function eventObjectToFormArray ($ev)
	{	
		$data = array(
				'eventTitle' => $ev->title,
				'eventStart' => $ev->start_date,
				'eventEnd' => $ev->end_date,
				'eventAllDay' => $ev->all_day,
				'eventLocation' => $ev->location,
				'eventProvider' => $ev->course_provider,
				'eventDiciplin' => '', //@TODO - add diciplin to DB
				'eventSummary' => $ev->summary,
				'eventDescription' => $ev->description,
				'eventStartTime' => '00:00', //@TODO - start and end times
				'eventEndTime' => '00:00');
		return $data;
	}
	
	function formArrayToEventObject ($data)
	{
		// Insert columns.
		$ev = new stdClass();
		$ev->created_by = JFactory::getUser()->id;
		$ev->calendar = 0;  //@TODO - implement the types of calendar
		$ev->location = $data['eventLocation'];
		$ev->title = $data['eventTitle'];
		$ev->start_date = $this->sqlDateTime($data['eventStart']);
		$ev->end_date = $this->sqlDateTime($data['eventEnd']);
		$ev->all_day = $data['eventAllDay'];
		$ev->course_provider = $data['eventProvider'];
		$ev->summary = $data['eventSummary'];
		$ev->description = $data['eventDescription'];
		
		return $ev;
	}
	
	
	
	
}