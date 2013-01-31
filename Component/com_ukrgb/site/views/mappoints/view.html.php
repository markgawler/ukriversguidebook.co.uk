<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Ukrgb Component
*/
class UkrgbViewMapPoints extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$this->set('MapPoint',123);
		error_log('HTML map points');
		//$data = $this->get('MapPoints');
		$ppp = JRequest::get( 'post' );
		
		ob_start();
		var_dump($ppp);
		error_log(ob_get_contents());
		ob_end_clean();
		
		// Display the view
		//parent::display($tpl);
	}
}