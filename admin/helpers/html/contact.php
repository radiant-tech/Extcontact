<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JLoader::register('ExtcontactHelper', JPATH_ADMINISTRATOR . '/components/com_extcontact/helpers/extcontact.php');

/**
 * @package     com_extcontact
*/
abstract class JHtmlContact
{
	/**
	 * Get the associated language flags
	 *
	 * @param   int  $contactid  The item id to search associations
	 *
	 * @return  string  The language HTML
	 */
	public static function association($contactid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = JLanguageAssociations::getAssociations('com_extcontact', '#__extcontact_details', 'com_extcontact.item', $contactid))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated contact items
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
			->select('c.id, c.name as title')
			->select('l.sef as lang_sef')
			->from('#__extcontact_details as c')
			->select('cat.title as category_title')
			->join('LEFT', '#__categories as cat ON cat.id=c.catid')
			->where('c.id IN (' . implode(',', array_values($associations)) . ')')
			->join('LEFT', '#__languages as l ON c.language=l.lang_code')
			->select('l.image')
			->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (runtimeException $e)
			{
				throw new Exception($e->getMessage(), 500);

				return false;
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url = JRoute::_('index.php?option=com_extcontact&task=contact.edit&id=' . (int) $item->id);
					$tooltipParts = array(
							JHtml::_('image', 'mod_languages/' . $item->image . '.gif',
									$item->language_title,
									array('title' => $item->language_title),
									true
							),
							$item->title,
							'(' . $item->category_title . ')'
					);

					$item->link = JHtml::_('tooltip', implode(' ', $tooltipParts), null, null, $text, $url, null, 'hasTooltip label label-association label-' . $item->lang_sef);
				}
			}

			$html = JLayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}

	/**
	 * @param   int $value	The featured value
	 * @param   int $i
	 * @param   bool $canChange Whether the value can be changed or not
	 *
	 * @return  string	The anchor tag to toggle featured/unfeatured contacts.
	 * @since   1.6
	 */
	public static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
				0	=> array('disabled.png', 'contacts.featured', 'COM_EXTCONTACT_UNFEATURED', 'COM_EXTCONTACT_TOGGLE_TO_FEATURE'),
				1	=> array('featured.png', 'contacts.unfeatured', 'JFEATURED', 'COM_EXTCONTACT_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), null, true);
		if ($canChange)
		{
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html .'</a>';
		}

		return $html;
	}
}
