<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Extcontact component helper.
 */
class ExtcontactHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view - evaluates to true or false in addEntry()
	 * @return  void
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_EXTCONTACT_SUBMENU_CONTACTS'),
			'index.php?option=com_extcontact&view=contacts',
			$vName == 'contacts'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_EXTCONTACT_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_extcontact',
			$vName == 'categories'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('COM_EXTCONTACT_SUBMENU_SECTIONS'),
			'index.php?option=com_extcontact&view=sections',
			$vName == 'sections'
		);
	}
	
	/**
	 * Transform Section string to array of Section object
	 * 
	 * @param	string	$section Comma separated list of numeric section values
	 * @param	boolean	$published (optional) 
	 * 
	 */
	public static function getSections($section, $published = true)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id, name, link, published')
			->from('#__extcontact_sections')
			->order('name ASC')
		;

		if (!empty($section))
		{
			$query->where('id IN ('.$section.')');
		}

		if ($published === true)
		{
			$query->where('published = 1');
		}
		$db->setQuery($query);
		$names = array();
		$names = $db->loadObjectList();
		
		return $names;	
	}

}
