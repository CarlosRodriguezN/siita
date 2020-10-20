<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

// Launch the controller.defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once( JPATH_COMPONENT.DS.'controller.php' );
 
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
$classname = 'mapaController'.$controller;
$controller = new $classname( );
$controller = JController::getInstance('mapa');
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();
?>