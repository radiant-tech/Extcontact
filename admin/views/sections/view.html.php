<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Sections view class
 */
class ExtcontactViewSections extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * Display the sections
	 * 
	 * @return void
	 */
	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		
		ExtcontactHelper::addSubmenu('sections');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		// @todo: Ordering can be added later
		
		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar
	 * @todo	Maybe set ACL at later date, for now component level access is enough
	 */
	public function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/extcontact.php';
		
		// We only need component level actions
		$canDo = JHelperContent::getActions(0, 0, 'com_extcontact');
		$user = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_EXTCONTACT_MANAGER_SECTIONS'), 'contact');
		
		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('section.add');
		}
		
		if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
		{
			JToolbarHelper::editList('section.edit');
		}
		
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('sections.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('sections.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::checkin('sections.checkin');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'sections.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('sections.trash');
		}
				
		// @todo Add Help
		//		JToolbarHelper::help('JHELP_COMPONENTS_CONTACTS_CONTACTS');
		
		JHtmlSidebar::setAction('index.php?option=com_extcontact');
		
		$options = JHtml::_('jgrid.publishedOptions', array('published' => true, 'unpublished' => true, 'archived' => false, 'trash' => true, 'all' => true));
		
		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', $options, 'value', 'text', $this->state->get('filter.published'), true)
		);
		
	}
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
				'a.published' => JText::_('JSTATUS'),
				'a.name' => JText::_('JGLOBAL_TITLE'),
				'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
	
}