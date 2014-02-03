<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/models/category.php';
JLoader::register('ExtcontactHelper', JPATH_ADMINISTRATOR . '/components/com_extcontact/helpers/extcontact.php');

/**
 * HTML Contact View class for the Extcontact component
 */
class ExtcontactViewContact extends JViewLegacy
{
	protected $state;

	protected $form;

	protected $item;

	protected $return_page;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$state		= $this->get('State');
		$item		= $this->get('Item');
		$this->form	= $this->get('Form');

		// Get the parameters
		$params = JComponentHelper::getParams('com_extcontact');

		if ($item)
		{
			// If we found an item, merge the item parameters
			$params->merge($item->params);

			// Get Category Model data
			$categoryModel = JModelLegacy::getInstance('Category', 'ExtcontactModel', array('ignore_request' => true));
			$categoryModel->setState('category.id', $item->catid);
			$categoryModel->setState('list.ordering', 'a.name');
			$categoryModel->setState('list.direction', 'asc');
			$categoryModel->setState('filter.published', 1);

			$contacts = $categoryModel->getItems();
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		// check if access is not public
		$groups	= $user->getAuthorisedViewLevels();

		$return = '';

		if ((!in_array($item->access, $groups)) || (!in_array($item->category_access, $groups)))
		{
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}

		$options['category_id']	= $item->catid;
		$options['order by']	= 'a.default_con DESC, a.ordering ASC';

		// Handle email cloaking
		if ($item->email_to && $params->get('show_email'))
		{
			$item->email_to = JHtml::_('email.cloak', $item->email_to);
		}
		if ($params->get('show_street_address') || $params->get('show_suburb') || $params->get('show_state') || $params->get('show_postcode') || $params->get('show_country'))
		{
			if (!empty ($item->address) || !empty ($item->suburb) || !empty ($item->state) || !empty ($item->country) || !empty ($item->postcode))
			{
				$params->set('address_check', 1);
			}
		}
		else
		{
			$params->set('address_check', 0);
		}

		// Manage the display mode for contact detail groups
		switch ($params->get('contact_icons'))
		{
			case 1 :
				// text
				$params->set('marker_address',   JText::_('COM_EXTCONTACT_ADDRESS') . ": ");
				$params->set('marker_email',     JText::_('JGLOBAL_EMAIL') . ": ");
				$params->set('marker_telephone', JText::_('COM_EXTCONTACT_TELEPHONE') . ": ");
				$params->set('marker_fax',       JText::_('COM_EXTCONTACT_FAX') . ": ");
				$params->set('marker_mobile',    JText::_('COM_EXTCONTACT_MOBILE') . ": ");
				$params->set('marker_misc',      JText::_('COM_EXTCONTACT_OTHER_INFORMATION') . ": ");
				$params->set('marker_class',     'jicons-text');
				break;

			case 2 :
				// none
				$params->set('marker_address',   '');
				$params->set('marker_email',     '');
				$params->set('marker_telephone', '');
				$params->set('marker_mobile',    '');
				$params->set('marker_fax',       '');
				$params->set('marker_misc',      '');
				$params->set('marker_class',     'jicons-none');
				break;

			default :
				if ($params->get('icon_address'))
				{
					$image1 = JHtml::_('image', $params->get('icon_address', 'con_address.png'), JText::_('COM_EXTCONTACT_ADDRESS').": ", null, false);
				}
				else
				{
					$image1 = JHtml::_('image', 'contacts/'.$params->get('icon_address', 'con_address.png'), JText::_('COM_EXTCONTACT_ADDRESS').": ", null, true);
				}

				if ($params->get('icon_email'))
				{
					$image2 = JHtml::_('image', $params->get('icon_email', 'emailButton.png'), JText::_('JGLOBAL_EMAIL').": ", null, false);
				}
				else
				{
					$image2 = JHtml::_('image', 'contacts/'.$params->get('icon_email', 'emailButton.png'), JText::_('JGLOBAL_EMAIL').": ", null, true);
				}

				if ($params->get('icon_telephone'))
				{
					$image3 = JHtml::_('image', $params->get('icon_telephone', 'con_tel.png'), JText::_('COM_EXTCONTACT_TELEPHONE').": ", null, false);
				}
				else
				{
					$image3 = JHtml::_('image', 'contacts/'.$params->get('icon_telephone', 'con_tel.png'), JText::_('COM_EXTCONTACT_TELEPHONE').": ", null, true);
				}

				if ($params->get('icon_fax'))
				{
					$image4 = JHtml::_('image', $params->get('icon_fax', 'con_fax.png'), JText::_('COM_EXTCONTACT_FAX').": ", null, false);
				}
				else
				{
					$image4 = JHtml::_('image', 'contacts/'.$params->get('icon_fax', 'con_fax.png'), JText::_('COM_EXTCONTACT_FAX').": ", null, true);
				}

				if ($params->get('icon_misc'))
				{
					$image5 = JHtml::_('image', $params->get('icon_misc', 'con_info.png'), JText::_('COM_EXTCONTACT_OTHER_INFORMATION').": ", null, false);
				}
				else
				{
					$image5 = JHtml::_('image', 'contacts/'.$params->get('icon_misc', 'con_info.png'), JText::_('COM_EXTCONTACT_OTHER_INFORMATION').": ", null, true);
				}

				if ($params->get('icon_mobile'))
				{
					$image6 = JHtml::_('image', $params->get('icon_mobile', 'con_mobile.png'), JText::_('COM_EXTCONTACT_MOBILE').": ", null, false);
				}
				else
				{
					$image6 = JHtml::_('image', 'contacts/'.$params->get('icon_mobile', 'con_mobile.png'), JText::_('COM_EXTCONTACT_MOBILE').": ", null, true);
				}

				$params->set('marker_address',   $image1);
				$params->set('marker_email',     $image2);
				$params->set('marker_telephone', $image3);
				$params->set('marker_fax',       $image4);
				$params->set('marker_misc',      $image5);
				$params->set('marker_mobile',    $image6);
				$params->set('marker_class',     'jicons-icons');
				break;
		}

		// Add links to contacts
		if ($params->get('show_contact_list') && count($contacts) > 1)
		{
			foreach ($contacts as &$contact)
			{
				$contact->link = JRoute::_(ExtcontactHelperRoute::getContactRoute($contact->slug, $contact->catid));
			}
			$item->link = JRoute::_(ExtcontactHelperRoute::getContactRoute($item->slug, $item->catid));
		}
		
		// Convert Section string to array of section objects
		$sections = ExtcontactHelper::getSections($item->section);
		
		// use the menuitem id to get the link for each section
		if (!empty($sections)) {
			// Create a JMenu object
			$menu = $app->getMenu();
			foreach ($sections as $section)
			{
				if ($section->link != '')
				{
					$menuitem = $menu->getItem($section->link);
					$section->url = $menuitem->link; 
				}
			}
		}

		JHtml::_('behavior.formvalidation');

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->contact  = &$item;
		$this->params   = &$params;
		$this->return   = &$return;
		$this->state    = &$state;
		$this->item     = &$item;
		$this->user     = &$user;
		$this->contacts = &$contacts;
		$this->sections	= &$sections;

		$item->tags = new JHelperTags;
		$item->tags->getItemTags('com_extcontact.contact', $this->item->id);

		// Override the layout only if this is not the active menu item
		// If it is the active menu item, then the view and item id will match
		$active	= $app->getMenu()->getActive();
		if ((!$active) || ((strpos($active->link, 'view=contact') === false) || (strpos($active->link, '&id=' . (string) $this->item->id) === false)))
		{
			if ($layout = $params->get('contact_layout'))
			{
				$this->setLayout($layout);
			}
		}
		elseif (isset($active->query['layout']))
		{
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}

		$model = $this->getModel();
		$model->hit();
		$this->_prepareDocument();

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_EXTCONTACT_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// if the menu item does not concern this contact
		if ($menu && ($menu->query['option'] != 'com_extcontact' || $menu->query['view'] != 'contact' || $id != $this->item->id))
		{

			// If this is not a single contact menu item, set the page title to the contact title
			if ($this->item->name)
			{
				$title = $this->item->name;
			}
			$path = array(array('title' => $this->contact->name, 'link' => ''));
			$category = JCategories::getInstance('Extcontact')->get($this->contact->catid);

			while ($category && ($menu->query['option'] != 'com_extcontact' || $menu->query['view'] == 'contact' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => ExtcontactHelperRoute::getCategoryRoute($this->contact->catid));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->name;
		}
		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (!$this->item->metadesc && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		$mdata = $this->item->metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}
	}
}