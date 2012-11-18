<?php

/**
 * @version		0.1
 * @package		UKRGB - RiverGuide
 * @copyright	Copyright (C) 2012 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Ukrgb Component
 */
class UkrgbViewRiverGuide extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{	
		
		// Assign data to the view
		$this->message = "My River Guide View";
		
		// Get the guide Data
		$this->data = $this->get('GuideData');

		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
		
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Display the view
		parent::display($tpl);
	}
}
