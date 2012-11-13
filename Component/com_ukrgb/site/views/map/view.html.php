<?php

/**
 * @version		0.1
 * @package		UKRGB - Map
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
class UkrgbViewMap extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{	
		JHtml::_('behavior.framework');
		JHtml::_('script', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js');
		JHtml::_('script', 'components/com_ukrgb/views/map/js/map.js');
		JHtml::_('stylesheet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css');
		JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
		
		// Assign data to the view
		$this->message = "My Map View";
		
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
