<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class UkrgbControllerEvent extends JControllerBase
{
	public function execute()
	{
		// Get the document object.
		$document = JFactory::getDocument();

		$viewName = $this->input->getWord('view', 'event');
		$viewFormat = $document->getType();
		
		// Register the layout paths for the view
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/view/' . $viewName . '/tmpl', 'normal');

		$viewClass  = $this->prefix . 'View' . ucfirst($viewName) . ucfirst($viewFormat);
		$modelClass = $this->prefix . 'Model' . ucfirst($viewName);

		if (class_exists($viewClass))
		{
			$model = new $modelClass;
			$view = new $viewClass($model, $paths);
			$view->setLayout('form');
		
			// Push document object into the view.
			$view->document = $document;
			
			// Load form and bind data
			$form = $model->getForm();
			
			// Set form and data to the view
			$view->form = &$form;
				
			// Render view.
			echo $view->render();
		}
		return true;
	}
}
