<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');

//  Require helper file
JLoader::register( 'PanelHelper', dirname( __FILE__ ) . DS . 'helpers' . DS . 'panel.php' );
 
// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance( 'Panel' );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();