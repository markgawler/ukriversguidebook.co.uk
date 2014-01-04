<?php // No direct access
// Admin

defined( '_JEXEC' ) or die( 'Restricted access' );

//load classes
JLoader::registerPrefix('Ukrgb', JPATH_COMPONENT_ADMINISTRATOR);

//Load plugins
JPluginHelper::importPlugin('ukrgb');

//application
$app = JFactory::getApplication();

// Require specific controller if requested
$controller = $app->input->get('task','display');

// Create the controller
$classname = 'UkrgbController'.ucwords($controller);
$controller = new $classname();

// Perform the Request task
$controller->execute();