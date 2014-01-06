<?php

// Site - Map Model
defined('_JEXEC') or die;

class UkrgbModelEvent extends JModelDatabase
{

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();
		//$form = $this->loadForm('com_ukrgb.event', 'event', array('control' => 'jform', 'load_data' => $loadData));

		$form = $this->loadForm('com_ukrgb.event', 'event', array('control' => 'jform', 'load_data' => false));
		
		
		if (empty($form))
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
	 * @see     JForm
	 * @since   3.2
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);
	
		// Create a signature hash.
		$hash = sha1($source . serialize($options));
	
		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}
	
		
		// Get the form.
		// Register the paths for the form -- failing here
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/model/form', 'normal');
		//$paths->insert(JPATH_COMPONENT . '/model/field', 'normal');
		//$paths->insert(JPATH_COMPONENT . '/model/rule', 'normal');
	
	
		// Solution until JForm supports splqueue
		//JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		//JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		JForm::addFormPath(JPATH_COMPONENT . '/model/form');
		//JForm::addFieldPath(JPATH_COMPONENT . '/model/field');
		
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
	
			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
		//	$this->preprocessForm($form, $data);
	
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
	 * @see     JFormRule
	 * @see     JFilterInput
	 * @since   3.2
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
	
		return $data;
	}

}
