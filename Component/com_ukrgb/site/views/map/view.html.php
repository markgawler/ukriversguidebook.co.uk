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
		JHtml::_('script', 'http://openlayers.org/api/OpenLayers.js');
		JHtml::_('script', 'components/com_ukrgb/proj4js/lib/proj4js-compressed.js');
		JHtml::_('script', 'components/com_ukrgb/views/map/js/OpenSpace.js');
		JHtml::_('script', 'components/com_ukrgb/views/map/js/map-openlayers.js');
		JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
		
		//var_dump ($this->get('BasicMapData'));
		//var_dump($status);
		$params = json_encode(array(
				'url' => JURI::base() . 'index.php?option=com_ukrgb&tmpl=raw&format=json',
				'mapdata' => $this->get('BasicMapData')));
		
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration('var params = ' .$params.';');
		
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
