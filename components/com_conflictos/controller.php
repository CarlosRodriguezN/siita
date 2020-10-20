<?php

/**
 * @package			Template
 * @subpackage	Components
 * @copyright		Copyright (C) 2007 - 2009 NawesCorp. All rights reserved.
 * @license			GNU/GPL
 */
// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * Template Component Controller
 *
 * @package    Template
 * @subpackage Components
 */
class ConflictosController extends JController
{

    /**
     * Method to display the view
     * (Actually don't needed but added for academic purposes just to show
     * that the parent display method is called)
     *
     * @access    public
     */
    function display($cachable = false) 
    {
        
        // set default view if not set
        JRequest::setVar( 'view', JRequest::getCmd( 'view', 'temas' ) );

        // call parent behavior
        parent::display($cachable);
    }
    

}
