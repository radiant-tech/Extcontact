<?php
defined('_JEXEC') or die;

abstract class modExtcontactRelatedHelper
{
	public static function getList(&$params)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$activeItem = $menu->getActive();
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
		->select('related')
		->from('#__extcontact_sections')
		->where('link='.$activeItem->id)
		;
		$db->setQuery($query);
		try
		{
			$related = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, $e->getMessage());
			return false;
		}
		
		$sections = array();
		if (!empty($related))
		{
			// Convert $related string to array
			$related = explode(',', $related);
			
			foreach ($related as $item)
			{
				// Get section data joined over the menu table to prevent error if menuitem is unpublished
				$query = $db->getQuery(true)
					->select('a.id, a.name, a.link')
					->from('#__extcontact_sections as a')
					->join('LEFT', '#__menu AS m ON m.id=a.link')
					->where('a.id='.$item)
					->where('a.published=1')
					->where('m.published=1')
				;
				$db->setQuery($query);
				try 
				{
					$sections = $db->loadObjectList();
				}
				catch (RuntimeException $e)
				{
					JError::raiseError(500, $e->getMessage());
					return false;
				}
			}
			return $sections;
		}
	} 
}