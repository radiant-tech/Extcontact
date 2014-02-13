<?php
defined('_JEXEC') or die;

?>
<?php if (!empty($list)) : ?>
<div class="extcontactrelated<?php echo $moduleclass_sfx; ?>">
	<ul>
	<?php foreach ($list as $section) : ?>
		<li>
		<?php if ($section->link) : ?>
			<a href="<?php echo JRoute::_('index.php?option=com_content&Itemid='.$section->link); ?>"><?php echo $section->name; ?></a>
		<?php else : ?>
			<?php echo $section->name; ?>
		<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>