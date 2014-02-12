<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<div class="contact-address box3 title2">
	<h3 class="rt-uppercase"><?php echo JText::_('COM_EXTCONTACT_CONTACT'); ?></h3>
	<?php if ($this->params->get('show_street_address') > 0) : ?>
		<?php echo ($this->contact->address ? $this->contact->address : $this->params->get('default_street_address')) .'<br />'; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_suburb') > 0) : ?>
		<?php echo ($this->contact->suburb ? $this->contact->suburb : $this->params->get('default_suburb')) .', '; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_state') > 0) : ?>
		<?php echo ($this->contact->state ? $this->contact->state : $this->params->get('default_state')) .' &nbsp;'; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_postcode') > 0) : ?>
		<?php echo ($this->contact->postcode ? $this->contact->postcode : $this->params->get('default_postcode')) .'<br />'; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_telephone') > 0 && $this->contact->telephone != '') : ?>
		<?php echo 'Tel: '.$this->contact->telephone .'<br />'; ?>
	<?php endif; ?>
	<?php if ($this->params->get('show_email') > 0 && $this->contact->email != '') : ?>
		<?php echo '<a href="mailto:'.$this->contact->email.'">'.$this->contact->email.'</a>'; ?>
	<?php endif; ?>
</div>