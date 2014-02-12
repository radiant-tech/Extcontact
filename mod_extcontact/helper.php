<?php
defined('_JEXEC') or die;

abstract class modExtcontactContactsHelper
{
	public static function getList(&$params)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$activeItem = $menu->getActive();
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
			->select('id')
			->from('#__extcontact_sections')
			->where('link='.$activeItem->id)
		;
		$db->setQuery($query);
		try 
		{
			$section_id = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseError(500, $e->getMessage());
			return false;
		}
		
		if (!empty($section_id))
		{
			$query = $db->getQuery(true)
			->select('a.id, a.name, a.catid, a.section, a.ordering')
			->from('#__extcontact_details AS a')
			->join('LEFT', '#__categories AS c ON c.id=a.catid')
			->where('LOCATE('.$section_id.', section)')
			->order('c.lft ASC, a.ordering ASC')
			;
			$db->setQuery($query);
			
			$items = array();
			try
			{
				$items = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				JError::raiseError(500, $e->getMessage());
				return false;
			}
				
			$contacts = array();
			foreach ($items as $item)
			{
				$sections = explode(',', $item->section);
				if (in_array($section_id, $sections))
				{
					$contacts[] = $item;
				}
			}
			
			// Identify the Itemid for each contact
			$link = 'index.php?option=com_extcontact&view=contact&id=';
			foreach ($contacts as $contact)
			{
				$menuItem = $menu->getItems(array('link'), array($link.$contact->id), true);
				if (!empty($menuItem))
				{
					$contact->link = $link . $contact->id . '&Itemid='.$menuItem->id;
				}
			}
		}
					
		return $contacts;
	}
}