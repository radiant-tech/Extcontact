<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Contact Component Category Tree
 */
class ExtcontactCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__extcontact_details';
		$options['extension'] = 'com_extcontact';
		$options['statefield'] = 'published';
		parent::__construct($options);
	}
}