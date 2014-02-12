<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Build the route for the com_extcontact component
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 */
function ExtcontactBuildRoute(&$query)
{
	$segments = array();

	// get a menu item based on Itemid or currently active
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$params = JComponentHelper::getParams('com_extcontact');
	$advanced = $params->get('sef_advanced_link', 0);

	if (empty($query['Itemid']))
	{
		$menuItem = $menu->getActive();
	}
	else
	{
		$menuItem = $menu->getItem($query['Itemid']);
	}
	$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if (isset($query['view']))
	{
		$view = $query['view'];
		if (empty($query['Itemid']) || empty($menuItem) || $menuItem->component != 'com_extcontact')
		{
			$segments[] = $query['view'];
		}
		unset($query['view']);
	}

	// are we dealing with a contact that is attached to a menu item?
	if (isset($view) && ($mView == $view) and (isset($query['id'])) and ($mId == (int) $query['id']))
	{
		unset($query['view']);
		unset($query['catid']);
		unset($query['id']);
		return $segments;
	}

	if (isset($view) and ($view == 'contact'))
	{
		if ($mId != (int) $query['id'] || $mView != $view)
		{
			if ($view == 'contact' && isset($query['id']))
			{
				if ($advanced)
				{
					list($tmp, $id) = explode(':', $query['id'], 2);
				}
				else
				{
					$id = $query['id'];
				}
				$segments[] = $id;
			}
				
		}
		unset($query['id']);
		unset($query['catid']);
	}

	if (isset($query['layout']))
	{
		if (!empty($query['Itemid']) && isset($menuItem->query['layout']))
		{
			if ($query['layout'] == $menuItem->query['layout'])
			{

				unset($query['layout']);
			}
		}
		else
		{
			if ($query['layout'] == 'default')
			{
				unset($query['layout']);
			}
		}
	}

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 */
function ExtcontactParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$item = $menu->getActive();
	$params = JComponentHelper::getParams('com_extcontact');
	$advanced = $params->get('sef_advanced_link', 0);

	// Count route segments
	$count = count($segments);

	// Standard routing for newsfeeds.
	if (!isset($item))
	{
		$vars['view'] = $segments[0];
		$vars['id'] = $segments[$count - 1];
		return $vars;
	}

	// From the categories view, we can only jump to a category.
	$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';

	$contactCategory = JCategories::getInstance('Extcontact')->get($id);

	$categories = ($contactCategory) ? $contactCategory->getChildren() : array();
	$vars['catid'] = $id;
	$vars['id'] = $id;
	$found = 0;
	foreach ($segments as $segment)
	{
		$segment = $advanced ? str_replace(':', '-', $segment) : $segment;
		foreach ($categories as $category)
		{
			if ($category->slug == $segment || $category->alias == $segment)
			{
				$vars['id'] = $category->id;
				$vars['catid'] = $category->id;
				$vars['view'] = 'category';
				$categories = $category->getChildren();
				$found = 1;
				break;
			}
		}
		if ($found == 0)
		{
			if ($advanced)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from('#__extcontact_details')
				->where($db->quoteName('catid') . ' = ' . (int) $vars['catid'])
				->where($db->quoteName('alias') . ' = ' . $db->quote($db->quote($segment)));
				$db->setQuery($query);
				$nid = $db->loadResult();
			}
			else
			{
				$nid = $segment;
			}
			$vars['id'] = $nid;
			$vars['view'] = 'contact';
		}
		$found = 0;
	}
	return $vars;
}
