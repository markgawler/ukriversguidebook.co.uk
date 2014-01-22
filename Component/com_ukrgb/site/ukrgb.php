<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) Mark Gawler All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Load classes
JLoader::registerPrefix('Ukrgb', JPATH_COMPONENT);

//Load plugins
//JPluginHelper::importPlugin('ukrgb');

// Application
$app = JFactory::getApplication();

$controllerHelper = new UkrgbControllerHelper;
$controller = $controllerHelper->parseController($app);
$controller->prefix = 'Ukrgb';

// Perform the Request task
$controller->execute();



