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
<form class="form-validate" method="post" name="eventForm" action="<?php echo JRoute::_('index.php')?>">

	
	<fieldset>
		<legend>
			<?php echo JText::_('UKRGB Events'); ?>
		</legend>
		<?php
		foreach ($this->form->getFieldset('eventSummary') as $field):
		?>
		<div class="">
			<div class="">
				<?php echo $field->label; ?>
			</div>
			<div class="">
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
		<div class="">
			<div class="">
				<?php echo $field->label; ?>
			</div>
			<div class="">
				<?php echo $field->input; ?>
			</div>
		</div>
		<?php
		endforeach;
		?>
	</fieldset>
	<fieldset>
		<input type="hidden" name="option" value="com_ukrgb" />
		<input type="hidden" name="task" value="eventsubmit" />
	
		<div class="btn-group pull-left">
			<button type="submit" class="btn validate">Submit form</button>		
		</div>
		<?php echo JHtml::_( 'form.token'); ?>
	</fieldset>	
</form>

