<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JLoader::register('ExtcontactHelper', JPATH_ADMINISTRATOR . '/components/com_extcontact/helpers/extcontact.php');
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

/**
 * Extcontact Component Association Helper
 */
abstract class ExtcontactHelperAssociation extends CategoryHelperAssociation
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since  3.0
	 */

	public static function getAssociations($id = 0, $view = null)
	{
		jimport('helper.route', JPATH_COMPONENT_SITE);

		$app = JFactory::getApplication();
		$jinput = $app->input;
		$view = is_null($view) ? $jinput->get('view') : $view;
		$id = empty($id) ? $jinput->getInt('id') : $id;

		if ($view == 'contact')
		{
			if ($id)
			{
				$associations = JLanguageAssociations::getAssociations('com_extcontact', '#__extcontact_details', 'com_extcontact.item', $id);

				$return = array();

				foreach ($associations as $tag => $item)
				{
					$return[$tag] = ExtcontactHelperRoute::getContactRoute($item->id, $item->catid, $item->language);
				}

				return $return;
			}
		}

		if ($view == 'category' || $view == 'categories')
		{
			return self::getCategoryAssociations($id, 'com_extcontact');
		}

		return array();

	}
}