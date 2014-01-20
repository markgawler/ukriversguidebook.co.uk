<?php
/**
 * @copyright	Copyright (C) 2011 Mark Gawler. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class UkrgbTableEvent extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__ukrgb_cal_events', 'id', $db);
	}

	/**
	 * Overload the store method for the Events table.
	 *
	 * @param	boolean	Toggle whether null values should be updated.
	 * @return	boolean	True on success, false on failure.
	 */
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		if ($this->id) {
			// Existing item
			$this->modified	= $date->toSql();
			$this->modified_by	= $user->get('id');
		} else {
			// New Event. Created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created) {
				$this->created = $date->toSql();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}
		
		// Set publish_up to null date if not set
		if (!$this->publish_up)
		{
			$this->publish_up = $this->_db->getNullDate();
		}
		
		// Set publish_down to null date if not set
		if (!$this->publish_down)
		{
			$this->publish_down = $this->_db->getNullDate();
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Event', 'UkrgbTable');
		if ($table->load(array('alias'=>$this->alias,'catid'=>$this->catid)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_UKRGB_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		// Attempt to store the user data.
		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return	boolean	True on success.
	 */
	public function check()
	{
		// check for existing name
		$db = $this->_db;
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from($db->quoteName('#__ukrgb_cal_events'));
		$query->where('title = ' . $db->quote($this->title) .
				' AND catid = ' . (int) $this->catid);
		$db->setQuery($query);

		$xid = intval($db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::_('COM_UKRGB_ERR_TABLES_NAME'));
			return false;
		}

		if (empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);
		if (trim(str_replace('-','',$this->alias)) == '') {
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		// Check the publish down date is not earlier than publish up.
		if (intval($this->publish_down) > 0 && $this->publish_down < $this->publish_up) {
			// Swap the dates.
			$temp = $this->publish_up;
			$this->publish_up = $this->publish_down;
			$this->publish_down = $temp;
		}

		return true;
	}
}
