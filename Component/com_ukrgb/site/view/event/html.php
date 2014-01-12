<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class UkrgbViewEventHtml extends JViewHtml
{ 
	public $form;
	
	public $data;
	
	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 */
	function render()
	{
		
		if ($this->layout == 'edit')
		{
	
			JHTML::_('behavior.formvalidation');
		
			//$app = JFactory::getApplication();		
	
			
			//retrieve task list from model
			//$model = new UkrgbModelEvent();
			
			
			//display
		}
		return parent::render();
	}
}
