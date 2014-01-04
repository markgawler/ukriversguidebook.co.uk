<?php defined( '_JEXEC' ) or die( 'Restricted access' );

// Admin - Map Controllwe
 
class UkrgbControllerMap extends JControllerBase
{
  public function execute()
  {

    // Get the application
    $app = $this->getApplication();
 
    // Get the document object.
    $document = JFactory::getDocument();
 
    $viewName = $app->input->getWord('view', 'map');
    $viewFormat = $document->getType();
    $layoutName = $app->input->getWord('layout', 'default');
    
    $app->input->set('view', $viewName);
 
    // Register the layout paths for the view
    $paths = new SplPriorityQueue;
    $paths->insert(JPATH_COMPONENT . '/view/' . $viewName . '/tmpl', 'normal');
 
    $viewClass = 'UkrgbView' . ucfirst($viewName) . ucfirst($viewFormat);
    $modelClass = 'UkrgbModel' . ucfirst($viewName);

    $view = new $viewClass(new $modelClass, $paths);

    $view->setLayout($layoutName);

    // Render our view.
    echo $view->render();
 
    return true;
  }

}
