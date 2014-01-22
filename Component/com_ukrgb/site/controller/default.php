<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Site - default controller

class UkrgbControllerDefault extends JControllerBase
{
	public function execute()
	{
		// Get the document object.
		$document = JFactory::getDocument();

		$viewName = $this->input->getWord('view', 'default');

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
				
			$view->setLayout('default');

			// Push document object into the view.
			$view->document = $document;
				
			// Reply for service requests
			if ($viewFormat == 'json')
			{
				return $view->render();
			}
				
			// Render view.
			echo $view->render();
		}
		return true;
	}
}
?>