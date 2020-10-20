<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Template Component Controller
 *
 * @package    Template
 * @subpackage Components
 */
class PoaController extends JController
{
    /**
     * display task
     *
     * @return void
     */
    function display($cachable = false)
    {
        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd( 'view', 'poas' ) );
            
        // call parent behavior
        parent::display($cachable);
    }
}