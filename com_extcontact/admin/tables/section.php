<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * @package     com_extcontact
 */
class ExtcontactTableSection extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__extcontact_sections', 'id', $db);
	}

	/**
	 * Stores a Section
	 *
	 * @param   boolean  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 */
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id == 0)
		{
			$this->created 		= $date->toSql();
			$this->created_by 	= $user->get('id');
		}

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 */
	public function check()
	{
		/** check for valid name */
		if (trim($this->name) == '')
		{
			$this->setError(JText::_('COM_EXTCONTACT_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		return true;
	}
}