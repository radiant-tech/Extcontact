<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Installation script
 *
 */
class com_extcontactInstallerScript
{
	function install($parent)
	{
		$parent->getParent()->setRedirectURL('index.php?option=com_extcontact');
	}

	function uninstall($parent)
	{
		echo '<p>' . JText::_('COM_EXTCONTACT_UNINSTALL_TEXT') . '</p>';
	}

	function update($parent)
	{
		echo '<p>' . JText::_('COM_EXTCONTACT_UPDATE_TEXT') . '</p>';
	}

	function preflight($type, $parent)
	{
		echo '<p>' . JText::_('COM_EXTCONTACT_PREFLIGHT_'.$type.'_TEXT') . '</p>';
	}

	function postflight($type, $parent)
	{
		echo '<p>' . JText::_('COM_EXTCONTACT_POSTFLIGHT_'.$type.'_TEXT') . '</p>';
	}

}