<?php
defined('_JEXEC') or die;

?>
<?php if (!empty($list)) : ?>
<div class="extcontact<?php echo $moduleclass_sfx; ?>">
	<ul>
	<?php foreach ($list as $contact) : ?>
		<li>
		<?php if ($contact->link) : ?>
			<a href="<?php echo $contact->link; ?>"><?php echo $contact->name; ?></a>
		<?php else : ?>
			<?php echo $contact->name; ?>
		<?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
<?php else : ?>
<p>No Related Contacts found</p>
<?php endif; ?>