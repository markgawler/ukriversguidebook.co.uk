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
<form class="form-validate form-horizontal" method="post"
	name="eventForm" action="<?php echo JRoute::_('index.php')?>">


	<fieldset>
		<legend>
			<?php echo JText::_('UKRGB Events'); ?>
		</legend>
		<?php
		foreach ($this->form->getFieldset('eventSummary') as $field):
		?>
		<div class="control-group">
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		</div>
		<?php
		endforeach;
		?>
	</fieldset>
	<fieldset>
		<?php
		foreach ($this->form->getFieldset('eventDetails') as $field):
		?>
		<div class="control-group">
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
		</div>
		<?php
		endforeach;
		?>
	</fieldset>
	<fieldset>
		<input type="hidden" name="evid" value="<?php echo $this->eventId; ?>" />
		<input type="hidden" name="option" value="com_ukrgb" /> <input
			type="hidden" name="task" value="eventsubmit" />

		<div class="btn-group pull-left">
			<button type="submit" class="btn btn-primary validate">Submit</button>
		</div>
		<?php echo JHtml::_( 'form.token'); ?>
	</fieldset>
</form>

