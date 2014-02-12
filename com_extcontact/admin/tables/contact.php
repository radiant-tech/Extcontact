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
class ExtcontactTableContact extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__extcontact_details', 'id', $db);
	}

	/**
	 * Overloaded bind function
	 *
	 * @param   array  $array   Named array to bind
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
		
		

		return parent::bind($array, $ignore);
	}

	/**
	 * Stores a contact
	 *
	 * @param   boolean  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 */
	public function store($updateNulls = false)
	{
		// Transform the params field
		if (is_array($this->params))
		{
			$registry = new JRegistry;
			$registry->loadArray($this->params);
			$this->params = (string) $registry;
		}

		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id)
		{
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		}
		else
		{
			// New contact. A contact created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		// Set publish_up to null date if not set
		if (!$this->publish_up)
		{
			$this->publish_up = $this->_db->getNullDate();
		}

		// Set publish_down to null date if not set
		if (!$this->publish_down)
		{
			$this->publish_down = $this->_db->getNullDate();
		}

		// Set xreference to empty string if not set
		if (!$this->xreference)
		{
			$this->xreference = '';
		}

		// Store utf8 email as punycode
		$this->email_to = JStringPunycode::emailToPunycode($this->email_to);

		// Convert IDN urls to punycode
		$this->webpage = JStringPunycode::urlToPunycode($this->webpage);

		// Verify that the alias is unique
		$table = JTable::getInstance('Contact', 'ExtcontactTable');
		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_EXTCONTACT_ERROR_UNIQUE_ALIAS'));

			return false;
		}

		// Turn the section array into a comma separated string to store
		// Note if SaveAjaxOrder is called, $this->section will still be a string
		if ($this->section && is_array($this->section))
		{
			$this->section = (string) implode(',', $this->section);
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
		$this->default_con = (int) $this->default_con;

		if (JFilterInput::checkAttribute(array ('href', $this->webpage)))
		{
			$this->setError(JText::_('COM_EXTCONTACT_WARNING_PROVIDE_VALID_URL'));

			return false;
		}

		/** check for valid name */
		if (trim($this->name) == '')
		{
			$this->setError(JText::_('COM_EXTCONTACT_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		if (empty($this->alias))
		{
			$this->alias = $this->name;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}
		/** check for valid category */
		if (trim($this->catid) == '')
		{
			$this->setError(JText::_('COM_EXTCONTACT_WARNING_CATEGORY'));

			return false;
		}

		// Check the publish down date is not earlier than publish up.
		if ((int) $this->publish_down > 0 && $this->publish_down < $this->publish_up)
		{
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		// Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey))
		{
			// Only process if not empty
			$bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey); // remove bad characters
			$keys = explode(',', $after_clean); // create array using commas as delimiter
			$clean_keys = array();

			foreach($keys as $key)
			{
				if (trim($key)) {  // ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			$this->metakey = implode(", ", $clean_keys); // put array back together delimited by ", "
		}

		// Clean up description -- eliminate quotes and <> brackets
		if (!empty($this->metadesc))
		{
			// Only process if not empty
			$bad_characters = array("\"", "<", ">");
			$this->metadesc = JString::str_ireplace($bad_characters, "", $this->metadesc);
		}

		return true;
	}
}
