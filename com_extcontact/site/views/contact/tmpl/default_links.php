<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
?>

<?php if ($this->params->get('presentation_style') == 'sliders') : ?>
	<?php echo JHtml::_('bootstrap.addSlide', 'slide-contact', JText::_('COM_EXTCONTACT_LINKS'), 'display-links'); ?>
<?php endif; ?>
<?php if ($this->params->get('presentation_style') == 'tabs') : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'display-links', JText::_('COM_EXTCONTACT_LINKS', true)); ?>
<?php endif; ?>
<?php if ($this->params->get('presentation_style') == 'plain'):?>
	<?php echo '<h3>'. JText::_('COM_EXTCONTACT_LINKS').'</h3>';  ?>
<?php endif; ?>

<div class="contact-links">
	<ul class="nav nav-tabs nav-stacked">
		<?php
		foreach (range('a', 'e') as $char) :// letters 'a' to 'e'
			$link = $this->contact->params->get('link'.$char);
			$label = $this->contact->params->get('link'.$char.'_name');

			if (!$link) :
				continue;
			endif;

			// Add 'http://' if not present
			$link = (0 === strpos($link, 'http')) ? $link : 'http://'.$link;

			// If no label is present, take the link
			$label = ($label) ? $label : $link;
			?>
			<li>
				<a href="<?php echo $link; ?>">
					<?php echo $label; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>

<?php if ($this->params->get('presentation_style') == 'sliders') : ?>
	<?php echo JHtml::_('bootstrap.endSlide'); ?>
<?php endif; ?>
<?php if ($this->params->get('presentation_style') == 'tabs') : ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>