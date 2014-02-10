<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * View to edit a Section.
 */
class ExtcontactViewSection extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Only concerned with component level permissions
		$canDo		= JHelperContent::getActions('com_extcontact', '', (int) $this->item->id);

		JToolbarHelper::title(JText::_('COM_EXTCONTACT_MANAGER_SECTION'), 'contact');

		// Build the actions for new and existing records.
		if ($isNew)
		{

			JToolbarHelper::apply('section.apply');
			JToolbarHelper::save('section.save');
			JToolbarHelper::cancel('section.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('section.apply');
					JToolbarHelper::save('section.save');
				}
			}

			JToolbarHelper::cancel('section.cancel', 'JTOOLBAR_CLOSE');
		}

//		JToolbarHelper::divider();
//		JToolbarHelper::help('JHELP_COMPONENTS_CONTACTS_CONTACTS_EDIT');
	}
}