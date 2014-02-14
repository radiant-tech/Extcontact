<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

JFormHelper::loadFieldClass('list');

class JFormFieldRelated extends JFormFieldList
{
	protected $type = 'Related';

	/**
	 * Method to get the Options
	 */
	protected function getOptions()
	{
		// Get the current section id
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'INT');
		
		// Return all sections, including unpublished, but not trashed
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id, name')
			->from('#__extcontact_sections')
			->where('published!=-2')
			->where('id!='.$id)
			->order('name ASC')
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