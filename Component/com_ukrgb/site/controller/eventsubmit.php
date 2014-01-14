<?php
/**
 * @package     Ukrgb.event
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) 2014  Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class UkrgbControllerEventSubmit extends JControllerBase
{     
	/**
	 * Application object - Redeclared for proper typehinting
	 *
	 * @var    JApplicationCms
	 */
	//protected $app;

	/**
	 * Method to save an Event.
	 *
	 * @return  boolean  True on success.
	 */
	public function execute()
	{
		// Check for request forgeries.
		if (!JSession::checkToken())
		{
			$this->app->enqueueMessage(JText::_('JINVALID_TOKEN'));
			$this->app->redirect('index.php');
		}
		

		$formModel = new UkrgbModelEventform;
		$form  = $formModel->getForm(null);
		$data  = $this->input->post->get('jform', array(), 'array');

		// Validate the posted data.
		$return = $formModel->validate($form, $data);

		
		// Check for validation errors.
		if ($return === false)
		{
			/*
			 * The validate method enqueued all messages for us, so we just need to redirect back.
			*/
			
			// Save the data in the session.
			$this->app->setUserState('com_ukrgb.event.data', $data);
							
			// Redirect back to the edit screen.
			$this->app->redirect(JRoute::_('index.php?option=com_ukrgb&task=event&layout=edit', false));		
		}
		
		// Attempt to save the configuration.
		$data = $return;
		$model = new UkrgbModelEvent();
		$model->store($data);
		
		// Check the return value.
		if ($return === false)
		{
			/*
			 * The save method enqueued all messages for us, so we just need to redirect back.
			*/
		
			// Save the data in the session.
			$this->app->setUserState('com_ukrgb.event.data', $data);				
			
			// Save failed, go back to the screen and display a notice.
			$this->app->redirect(JRoute::_('index.php?option=com_ukrgb&task=event&layout=edit', false));
		}
		
		// Redirect back to com_config display
		$this->app->enqueueMessage(JText::_('Evens Saved Sucsessfully'));
		$this->app->redirect(JRoute::_('index.php?option=com_ukrgb&task=event', false));
		
		return true;
	}
}
