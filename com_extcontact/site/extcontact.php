<?php
/**
 * @package	com_extcontact
 * @copyright	Copyright 2014 Denise McLaurin
 * @license	GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/route.php';

$controller = JControllerLegacy::getInstance('Extcontact');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
