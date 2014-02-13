<?php
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$list = modExtcontactRelatedHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_extcontactrelated', $params->get('layout', 'default'));