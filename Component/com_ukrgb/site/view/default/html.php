<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class UkrgbViewDefaultHtml extends JViewHtml
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $this->getLayout();


		$this->params = JComponentHelper::getParams('com_ukrgb');

		//retrieve task list from model
		$model = new UkrgbModelDefault();

		$this->message = $model->getMessage();

		//if($layout == 'list')
		//{
		//	$this->books = $model->listItems();
		//	$this->_bookListView = LendrHelpersView::load('Book','_entry','phtml');
		//} else {
		//}

		//display
		return parent::render();
	}
}