<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
class UkrgbViewDefaultHtml extends JViewHtml
{
  function render()
  {
    $app = JFactory::getApplication();
   
    //retrieve task list from model
    $model = new UkrgbModelDefault();
    $stuff = $model->getStuff();
	$this->stuff = $stuff[0] . $stuff[1];
    
    $this->addToolbar();

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