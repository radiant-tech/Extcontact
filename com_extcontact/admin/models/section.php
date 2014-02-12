<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JLoader::register('ExtcontactHelper', JPATH_ADMINISTRATOR . '/components/com_extcontact/helpers/extcontact.php');

/**
 * Item Model for a Section
*/
class ExtcontactModelSection extends JModelAdmin
{
	/**
	 * The type alias for this content type.
	 */
	public $typeAlias = 'com_extcontact.section';

	/**
	 * Method to test whether a record can be deleted.
	 * Overrides JModelAdmin method. If the item hasn't been sent to trash yet, it can't be deleted.
	 * 
	 * @param   object  $record  A record object.
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->published != -2)
			{
				return;
			}
			$user = JFactory::getUser();
			return $user->authorise('core.delete', 'com_extcontact');
		}
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type      $type      The table type to instantiate
	 * @param   string    $prefix    A prefix for the table class name. Optional.
	 * @param   array     $config    Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'Section', $prefix = 'ExtcontactTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the row form.
	 *
	 * @param   array      $data        Data for the form.
	 * @param   boolean    $loadData    True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_extcontact.section', 'section', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
	
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_extcontact.edit.section.data', array());
	
		if (empty($data))
		{
			$data = $this->getItem();
		}
	
		$this->preprocessData('com_extcontact.section', $data);
	
		return $data;
	}
	
}