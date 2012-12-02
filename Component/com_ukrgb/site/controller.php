<?php

/**
 * @version		$Id: controller.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
jimport('joomla.client.http');
/**
 * UkrgbController Component Controller
 */
class UkrgbController extends JController
{
	function donation()
	{
		$input = JFactory::getApplication()->input;
		$input->set('view','donation');
		parent::display();
	}
	
	function map()
	{
		$input = JFactory::getApplication()->input;
		$mapid = $input->get ('mapid');
		if (isset($mapid)){
			$input->set('view','map');
		}
		parent::display();
	}
	
	function mapPoints()
	{
		$input = JFactory::getApplication()->input;
		$nw = $input->get ('nw');
		$se = $input->get ('se');	
		if (isset($nw) && isset($se)){
			$input->set('view','mappoints');	
		}
		parent::display();
	}
	
	function riverGuide()
	{
		$input = JFactory::getApplication()->input;
		$input->set('view','riverguide');
		parent::display();
	}
}
