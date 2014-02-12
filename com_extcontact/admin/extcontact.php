<?php
/**
 * @package	com_extcontact
* @copyright	Copyright 2014 Denise McLaurin
* @license		GNU General Public License version 2 or later
*/
// No direct access
defined('_JEXEC') or die;

if (!JFactory::getUser()->authorise('core.manage','com_extcontact'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Get an instance of the controller
$controller = JControllerLegacy::getInstance('Extcontact');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();