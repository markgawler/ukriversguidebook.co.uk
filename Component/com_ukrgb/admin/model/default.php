<?php
class UkrgbModelDefault extends JModelBase
{

	public function getStuff()
	{
		$db = JFactory::getDbo();

		$values = array("hello","world");


		return $values;
	}

}

