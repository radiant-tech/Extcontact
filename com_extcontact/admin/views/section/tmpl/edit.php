<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'section.cancel' || document.formvalidator.isValid(document.id('section-form')))
		{
			<?php //echo $this->form->getField('misc')->save(); ?>
			Joomla.submitform(task, document.getElementById('section-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_extcontact&view=section&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="section-form" class="form-validate">

	<div class="form-inline form-inline-header">
		<?php echo $this->form->getControlGroup('name'); ?>
	</div>
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('published'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('link'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('link'); ?></div>
			</div>
		</div>		
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>