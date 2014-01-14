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
		$layout = $this->input->getWord('layout', 'display');
		$id = $this->input->getInt('evid', null);
		
		// Register the layout paths for the view
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_COMPONENT . '/view/' . $viewName . '/tmpl', 'normal');

		$viewClass  = $this->prefix . 'View' . ucfirst($viewName) . ucfirst($viewFormat);
		$modelClass = $this->prefix . 'Model' . ucfirst($viewName);

		if (class_exists($viewClass))
		{
			$model = new $modelClass;
			$view = new $viewClass($model, $paths);
			$view->setLayout($layout);
		
			// Push document object into the view.
			$view->document = $document;
			$view->eventId = $id;
			if ($id != null)
			{
				$data = $model->load($id);
			}
			else
			{
				$data = null;
			}
			
			if ($layout == 'edit')
			{					
				$formModel = new UkrgbModelEventform;
				$form = $formModel->getForm($data);

				// Set form and data to the view
				$view->form = &$form;
			}
	
			// Render view.
			echo $view->render();
		}
		return true;
	}
}
