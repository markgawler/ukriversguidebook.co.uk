<?php defined( '_JEXEC' ) or die( 'Restricted access' );

// Admin - Map View

class UkrgbViewMapHtml extends JViewHtml
{
	function render()
	{
		$app = JFactory::getApplication();
		 
		//retrieve task list from model
		$model = new UkrgbModelMap();
		$this->addToolbar();

		$mapid = $app->input->getInt('mapid', 0);
		$mapParams = $model->getMapParameters($mapid);

		var_dump($mapParams);

		//display
		return parent::render();
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		//$canDo = LendrHelpersLendr::getActions();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('UKRGB Stuff'));

		//if ($canDo->get('core.admin'))
		//{
		JToolbarHelper::preferences('com_ukrgb');
		//}
		}

	}