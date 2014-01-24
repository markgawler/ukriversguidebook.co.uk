<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) 2005 - 2014 Mark Gawler, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Ukrgb  Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_ukrgb
 *
 */
class UkrgbController extends JControllerLegacy
{
	/**
	 * @var		string	The default view.
	 */
	protected $default_view = 'eventmanager';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean			$cachable	If true, the view output will be cached
	 * @param   array  $urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController		This object to support chaining.

	 */
	public function display($cachable = false, $urlparams = false)
	{
		
		
		require_once JPATH_COMPONENT.'/helpers/ukrgb.php';

		$view   = $this->input->get('view', 'eventmanager');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
			
		
		// Check for edit form.
		if ($view == 'event' && $layout == 'edit' && !$this->checkEditId('com_ukrgb.edit.event', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_ukrgb&view=eventmanager', false));

			return false;
		}

		parent::display();

		return $this;
	}
}
