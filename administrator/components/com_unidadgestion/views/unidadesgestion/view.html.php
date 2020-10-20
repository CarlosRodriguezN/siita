<?php

/**
 * @version		$Id: view.html.php 60 2010-11-27 18:45:40Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/programa_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla view library
jimport( 'joomla.application.component.view' );

/**
 * HelloWorlds View
 */
class UnidadGestionViewUnidadesGestion extends JView
{

    protected $items;
    protected $pagination;
    protected $canDo;

    /**
     * HelloWorlds view display method
     * @return void
     */
    function display( $tpl = null )
    {
        // Get data from the model
        $items = $this->get( 'Items' );
        $pagination = $this->get( 'Pagination' );

        // What Access Permissions does this user have? What can (s)he do?
        $this->canDo = UnidadGestionHelper::getActions();

        // Check for errors.
        if( count( $errors = $this->get( 'Errors' ) ) ){
            JError::raiseError( 500, implode( '<br />', $errors ) );
            return false;
        }
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display( $tpl );

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar()
    {
        JToolBarHelper::title( JText::_( 'COM_HELLOWORLD_MANAGER_HELLOWORLDS' ), 'unidadgestion' );
        if( $this->canDo->get( 'core.admin' ) ){
            JToolBarHelper::divider();
            JToolBarHelper::preferences( 'com_unidadgestion' );
        }
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $document = JFactory::getDocument();
        $document->setTitle( JText::_( 'COM_HELLOWORLD_ADMINISTRATION' ) );
    }

}
