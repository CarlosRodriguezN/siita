<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 *	Controlador General del componente Indicadores 
 */
class ApiRestController extends JController
{
    /**
    * display task
    *
    * @return void
    */
    function display($cachable = false) 
    {
        if ( JFactory::getUser()->guest == 0 ) {
            // set default view if not set
            JRequest::setVar( 'view', JRequest::getCmd( 'view', 'urls' ) );

            // call parent behavior
            parent::display($cachable);
        } else if ( !JFactory::getUser()->authorise( 'core.admin', 'com_apirest' ) ){
            return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
        }
        
        
        
    }
}