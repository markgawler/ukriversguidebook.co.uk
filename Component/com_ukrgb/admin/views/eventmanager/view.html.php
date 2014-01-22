<?php
/**
 * @copyright	Copyright (C) 2011 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of events.
 *
*/
class UkrgbViewEventManager extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		UkrgbHelper::addSubmenu('eventmanager');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		JLoader::register('UkrgbHelper', JPATH_COMPONENT.'/helpers/ukrgb.php');

		$state	= $this->get('State');
		$canDo	= JHelperContent::getActions($state->get('filter.category_id'), 0, 'com_ukrgb');
		//$canDo	= UkrgbHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();

		JToolBarHelper::title(JText::_('COM_UKRGB_MANAGER_EVENTS'), 'newsfeeds.png');
		if (count($user->getAuthorisedCategories('com_ukrgb', 'core.create')) > 0) {
			JToolBarHelper::addNew('event.add','JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('event.edit','JTOOLBAR_EDIT');
		}

		// Add export toolbar
		$bar = JToolBar::getInstance('toolbar');
		//$bar->appendButton('Link', 'export', 'COM_JOOMPROSrgbUBS_TOOLBAR_CSVREPORT',
		//	'index.php?option=com_ukrgb&task=submanager.csvreport');

		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::publish('eventmanager.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('eventmanager.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolBarHelper::divider();
			JToolBarHelper::archiveList('eventmanager.archive');
			JToolBarHelper::checkin('eventmanager.checkin');
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'eventmanager.delete','JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('eventmanager.trash','JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_ukrgb');
			JToolBarHelper::divider();
		}

		JToolBarHelper::help('', '', JText::_('COM_UKRGB_EVENTMANAGER_HELP_LINK'));
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 */
	protected function getSortFields()
	{
		return array(
				'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
				'a.state' => JText::_('JSTATUS'),
				'a.title' => JText::_('JGLOBAL_TITLE'),
				'a.access' => JText::_('JGRID_HEADING_ACCESS'),
				'a.hits' => JText::_('JGLOBAL_HITS'),
				'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
				'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
