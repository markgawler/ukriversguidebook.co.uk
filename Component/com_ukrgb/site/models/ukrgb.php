<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * UKRGB Donation Model
*/
class UkrgbModelUkrgb extends JModelItem
{
	/**
	 * @var string msg
	 */
	protected $msg;

	/**
	 * Get the name
	 * @return string The message to be displayed to the user
	 */
	public function getMsg()
	{
		echo "Ukrgb Model";
		echo "<br>";
		if (!isset($this->msg))
		{
			$this->msg = 'UKRGB Blogs!';
		}

		return $this->msg;
	}
}