<?php

// Chequeo de acceso: se permite a este usuario para acceder al backend de este componente?
if ( JFactory::getUser()->guest ) {
    $app = JFactory::getApplication();
    $app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'error');
    $app->redirect();
} else if ( !JFactory::getUser()->authorise( 'core.admin', 'com_canastaproy' ) ){
    return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');

//  Require helper file
JLoader::register( 'CanastaProyHelper', JPATH_ADMINISTRATOR . DS . 'components'. DS . 'com_canastaproy'. DS .'helpers' . DS . 'canastaproy.php' );
 
// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance( 'Canastaproy' );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();
