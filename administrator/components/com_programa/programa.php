<?php

/**
 * @version		$Id: hello.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// Access check: is this user allowed to access the backend of this component?
if( !JFactory::getUser()->authorise( 'core.manage', 'com_programa' ) ){
    return JError::raiseWarning( 404, JText::_( 'JERROR_ALERTNOAUTHOR' ) );
}

//  No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

//  Require helper file
JLoader::register( 'ProgramaHelper', dirname( __FILE__ ) . DS . 'helpers' . DS . 'programa.php' );
  
//  Import joomla controller library
jimport( 'joomla.application.component.controller' );

//  Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance( 'Programa' );

//  Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );

//  Redirect if set by the controller
$controller->redirect();