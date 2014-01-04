<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) Mark Gawler. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for controllers
 *
 * @package     Joomla.Libraries
 * @subpackage  controller
 * @since       3.2
*/
class UkrgbControllerHelper
{
	public function parseController($app)
	{
		// Require specific controller if requested
		$tasks = array();
		$tasks = explode('.', $app->input->get('task','default'));
		
		$task = ucfirst(strtolower($tasks[0]));
		$activity = '';
		if (count($tasks)>1){
			$activity = ucfirst(strtolower($tasks[1]));
		}
				
		$controllerName = 'Ukrgb' . 'Controller' . $task . $activity;
		
		
		if (!class_exists($controllerName))
		{
			error_log("Error Log no such Controller: ".$controllerName);
			return false;
		}
		$controller = new $controllerName;
		return $controller;
	}
}
?>