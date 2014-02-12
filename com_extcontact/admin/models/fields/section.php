<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

JFormHelper::loadFieldClass('list');

class JFormFieldSection extends JFormFieldList
{
	protected $type = 'Section';
	
	/**
	 * Method to get the Options
	 */
	protected function getOptions()
	{
		// Get the published sections
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id, name')
			->from('#__extcontact_sections')
//			->where('published=1')
		;
		$db->setQuery($query);
		
		try 
		{
			$items = $db->loadObjectList();
		} 
		catch (Exception $e) 
		{
			JError::raiseWarning(500, $e->getMessage);
		}
		
		$options = array();
		foreach ($items as $item)
		{
			$options[] = JHtml::_('select.option', $item->id, $item->name);
		}
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
	}
}