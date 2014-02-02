<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams('com_media');

jimport('joomla.html.html.bootstrap');
?>
<div class="contact<?php echo $this->pageclass_sfx?>">
	<div class="pull-left rt-grid-2">
	<?php if ($this->params->get('show_image')) : ?>
		<div class="thumbnail">
		<?php if ($this->contact->image) : ?>
			<?php echo JHtml::_('image', $this->contact->image, JText::_('COM_EXTCONTACT_IMAGE_DETAILS'), array('align' => 'middle', 'class' => 'rt-image')); ?>
		<?php endif; ?>
		</div>
	<?php endif; ?>
		<div class="contact-links">
<!--  Vcard -->
	<?php if ($this->params->get('allow_vcard')) :	?>
			<a href="<?php echo JRoute::_('index.php?option=com_extcontact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
		<?php echo JText::_('COM_EXTCONTACT_DOWNLOAD_VCARD');?></a><br />
	<?php endif; ?>
<!-- Assistant -->
	<?php if ($this->params->get('show_assistant') && $this->contact->assistant != '') : ?>
		<?php echo JText::_('COM_EXTCONTACT_ASSISTANT') . $this->contact->assistant; ?><br />
	<?php endif; ?>
<!--  Social Media links -->
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
<!-- Address -->	
	<?php echo $this->loadTemplate('address'); ?>
<!-- Sections -->
	<?php if ($this->params->get('show_sections') && $this->contact->section) : ?>
		<div>
			<h3 class="rt-uppercase"><?php echo JText::_('COM_EXTCONTACT_SECTIONS'); ?></h3>
			<ul>
			<?php foreach ($this->sections as $section)
			{
				echo '<li>';
				if ($section->url != '')
				{
					echo '<a href="'.$section->url.'&amp;Itemid='.$section->link.'">'.$section->name.'</a>';
				}
				else 
				{
					echo $section->name;
				}
				echo '</li>';
			} ?>
			</ul>
		</div>
	<?php endif; ?>
	</div>
	
	<div class="pull-left rt-grid-9" style="padding-left:3%;">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
	<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
		<div class="page-header">
			<h2>
				<?php if ($this->item->published == 0) : ?>
					<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
				<?php endif; ?>
				<span class="contact-name"><?php echo $this->contact->name; ?></span>
			</h2>
		</div>
	<?php endif;  ?>

	<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
		<h3><?php echo $this->contact->con_position; ?></h3>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'overview')); ?>

<?php if ($this->contact->overview != '') : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'overview', JText::_('COM_EXTCONTACT_TAB_OVERVIEW', true)); ?>
		<div style="padding:2%;"><?php echo $this->contact->overview; ?></div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	
<?php if ($this->contact->education != '') : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'education', JText::_('COM_EXTCONTACT_TAB_EDUCATION', true)); ?>
		<div style="padding:2%;"><?php echo $this->contact->education; ?></div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	
<?php if ($this->contact->baradm != '') : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'baradm', JText::_('COM_EXTCONTACT_TAB_BARADM', true)); ?>
		<div style="padding:2%;"><?php echo $this->contact->baradm; ?></div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

<?php if ($this->contact->honors != '') : ?>	
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'honors', JText::_('COM_EXTCONTACT_TAB_HONORS', true)); ?>
		<div style="padding:2%;"><?php echo $this->contact->honors; ?></div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	
<?php if ($this->contact->orgs != '') : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'orgs', JText::_('COM_EXTCONTACT_TAB_ORGS', true)); ?>
		<div style="padding:2%;"><?php echo $this->contact->orgs; ?></div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	
<?php if ($this->contact->presentations != '') : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'presentations', JText::_('COM_EXTCONTACT_TAB_PRESENTATIONS')); ?>
		<div style="padding:2%;"><?php echo $this->contact->presentations; ?></div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>
	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	
	<div style="clear:both;"></div>

</div>