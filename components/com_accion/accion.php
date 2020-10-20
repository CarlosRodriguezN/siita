<?php

// Chequeo de acceso: se permite a este usuario para acceder al backend de este componente?
if ( JFactory::getUser()->guest ) {
    $app = JFactory::getApplication();
    $app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'error');
    $app->redirect();
} else if ( !JFactory::getUser()->authorise( 'core.admin', 'com_accion' ) ){
    return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

//  Require helper file
JLoader::register( 'AccionHelper', JPATH_ADMINISTRATOR . DS . 'components'. DS . 'com_accion'. DS .'helpers' . DS . 'accion.php' );

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once( JPATH_COMPONENT . DS . 'controller.php' );

// Get an instance of the controller prefixed accion
$controller = JController::getInstance( 'accion' );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();
