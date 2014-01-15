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
			// Access check.
			$user = JFactory::getUser(); 
			if ($user->guest)
			{
				$this->app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'error');
				return;
			}
			
			$model = new $modelClass;
			$view = new $viewClass($model, $paths);			
			$view->setLayout($layout);
				
			// Push document object into the view.
			$view->document = $document;
			if ($id != null)
			{
				$ev = $model->load($id);
				// Invalid events
				if (empty($ev))
				{	
					$this->app->enqueueMessage(JText::_('COM_UKRGB_EVENT_INVALID_ID'), 'error');
					return;
				}
				// prevent editing someoneelses event
				if ($ev->created_by != $user->id){
					$this->app->enqueueMessage(JText::_('COM_UKRGB_EVENT_AUTH_EDIT'), 'error');
					return;
				}
				$view->eventId = $id;
			}
			else
			{
				$ev = null;
				$view->eventId = null;
			}
			
			if ($layout == 'edit')
			{					
				$formModel = new UkrgbModelEventform;
				$form = $formModel->getForm($ev);

				// Set form and data to the view
				$view->form = &$form;
			}
	
			// Render view.
			echo $view->render();
		}
		return true;
	}
}
