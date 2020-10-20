<?php

// Chequeo de acceso: se permite a este usuario para acceder al backend de este componente?
if ( JFactory::getUser()->guest ) {
    $app = JFactory::getApplication();
    $app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'error');
    $app->redirect();
} else if ( !JFactory::getUser()->authorise( 'core.admin', 'com_actividad' ) ){
    return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance( 'actividad' );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();