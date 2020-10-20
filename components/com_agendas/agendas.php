<?php

// Chequeo de acceso: se permite a este usuario para acceder al backend de este componente?
if ( JFactory::getUser()->guest ) {
    $app = JFactory::getApplication();
    $app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'error');
    $app->redirect();
} else if ( !JFactory::getUser()->authorise( 'core.admin', 'com_agendas' ) ){
    return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

//  Require helper file
JLoader::register( 'AgendaHelper', JPATH_ADMINISTRATOR . DS . 'components'. DS . 'com_agendas'. DS .'helpers' . DS . 'agenda.php' );

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
jimport( 'joomla.application.component.controller' );

// Get an instance of the controller prefixed by HelloWorld

$controller = JController::getInstance( 'agendas' );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );

// Redirect if set by the controller
$controller->redirect();


