<?php

defined('_JEXEC') or die;
jimport('joomla.filesystem.path');
require_once dirname(__FILE__) . '/helper.php';

$oPlnVigente = new modCarruselHelper();
$lstProgramas = $oPlnVigente->getProgramas();
//var_dump($lstProgramas);exit();
$document = JFactory::getDocument();

require JModuleHelper::getLayoutPath('mod_carrusel', $params->get('layout', 'default'));
