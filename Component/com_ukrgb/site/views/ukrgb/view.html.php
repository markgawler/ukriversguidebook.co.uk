<?php

/**
 * @version		$Id: view.html.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Ukrgb Component
 */
class UkrgbViewUkrgb extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		// Assign data to the view
		$this->name = $this->get('Msg');
		
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
