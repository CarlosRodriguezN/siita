<?php

/**
 * @version	$Id: controller.php 15 2009-11-02 18:37:15Z chdemko $
 * @package	Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author	Christophe Demko
 * @link		
 * @license	License GNU General Public License version 2 or later
 */
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controller library
jimport( 'joomla.application.component.controller' );

/**
 * Controlador del Componente "administración contratos"
 */
class ContratosController extends JController
{

    /**
     * display task
     *
     * @return void
     */
    function display( $cachable = false )
    {

        // set default view if not set
        JRequest::setVar( 'view', JRequest::getCmd( 'view', 'contratos' ) );

        // call parent behavior
        parent::display( $cachable );
    }

}