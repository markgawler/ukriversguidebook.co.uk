<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * JSON View class for the Ukrgb Component
*/
class UkrgbViewMapPoints extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// Set up the data to be sent in the response.
		$data = $this->get('MapPoints');
		// Output the JSON data.
		echo json_encode($data);
	}
}