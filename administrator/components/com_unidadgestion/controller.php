<?php

/**
 * @version		$Id: controller.php 59 2010-11-27 14:17:52Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controller library
jimport( 'joomla.application.component.controller' );

/**
 * General Controller of HelloWorld component
 */
class UnidadGestionController extends JController
{

    /**
     * display task
     *
     * @return void
     */
    function display( $cachable = false )
    {
        // set default view if not set
        JRequest::setVar( 'view', JRequest::getCmd( 'view', 'UnidadesGestion' ) );

        // call parent behavior
        parent::display( $cachable );
    }

}
