<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_ukrgb
 *
 * @copyright   Copyright (C) . All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<h2>Events</h2>

<fieldset class="">
	<legend><?php echo JText::_('UKRGB Eveents'); ?></legend>
	<?php
	foreach ($this->form->getFieldset('eventDetails') as $field):
	?>
		<div class="control-group">
			<div class="control-label"><?php echo $field->label; ?></div>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>
	<?php
	endforeach;
	?>
</fieldset>