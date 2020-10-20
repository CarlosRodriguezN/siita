<?php

// Chequeo de acceso: se permite a este usuario para acceder al backend de este componente?
if ( JFactory::getUser()->guest ) {
    $app = JFactory::getApplication();
    $app->enqueueMessage(JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'error');
    $app->redirect();
} else if ( !JFactory::getUser()->authorise( 'core.admin', 'com_unidadgestion' ) ){
    return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

//  Require helper file
JLoader::register( 'UnidadGestionHelper', JPATH_ADMINISTRATOR . DS . 'components'. DS . 'com_unidadgestion'. DS .'helpers' . DS . 'unidadgestion.php' );
 
// Get an instance of the controller prefixed by Unidad de Gestion
$controller = JController::getInstance( 'UnidadGestion' );

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();
?>